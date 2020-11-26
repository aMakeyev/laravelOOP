<?php namespace Calc\Model;

use Auth;
use Str;
use Calc\Model\Traits\Statusable;
use Calc\Presenters\PresentableTrait;
use Input;

class Sendmail extends BaseModel
{
    use PresentableTrait, Statusable;

    protected $presenter = 'SendmailPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sendmails';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public static $sortable = [
        'id',
        'subject',
        'body',
        'target',
        'status',
        'created_at',
    ];

    protected $fillable = [
        'id',
        'subject',
        'body',
        'target',
        'status',
        'file',
    ];

    protected $appends = ['target_text', 'status_text', 'date', 'file_name'];

    public function getTargetTextAttribute()
    {
        static $targets;

        if ( ! $targets) $targets = \Config::get('calc::client/types');

        return isset($this->attributes['target'], $targets[$this->attributes['target']])
            ? $targets[$this->attributes['target']] : '';
    }

    public function getStatusTextAttribute()
    {
        static $statuses;

        if ( ! $statuses) $statuses = \Config::get('calc::sendmail/statuses');

        return isset($this->attributes['status'], $statuses[$this->attributes['status']])
            ? $statuses[$this->attributes['status']] : '';
    }

    /**
     * @return string
     */
    public function getFileNameAttribute()
    {
        if($this->file) {
            return basename($this->file);
        }

        return '';
    }

    /**
     * Получение форматированной даты
     *
     * @return null|string
     */
    public function getDateAttribute()
    {
        if ( ! isset($this->attributes['created_at'])) return null;

        return $this->created_at->format(self::DATE_FORMAT);
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param int                                   $target
     *
     * @return mixed
     */
    public function scopeTarget($q, $target)
    {
        if ($target = (int) $target)
        {
            $q->where('target', $target);
        }

        return $q;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param int                                   $status
     *
     * @return mixed
     */
    public function scopeStatus($q, $status)
    {
        if ($status = (int) $status)
        {
            $q->where('status', $status);
        }

        return $q;
    }

    /**
     * @return string
     */
    public static function saveFile()
    {
        if (Input::hasFile('file')) {
            $filePath = public_path('files/sendmails');
            Input::file('file')->move($filePath, Input::file('file')->getClientOriginalName());
            return '/files/sendmails/' . Input::file('file')->getClientOriginalName();
        }

        return '';
    }
}
