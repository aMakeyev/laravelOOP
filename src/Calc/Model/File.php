<?php namespace Calc\Model;

use Auth;

class File extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'files';

    /** EVENTS */

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $model)
        {
            if ( ! Auth::check())
            {
                return false;
            }

            $model->created_by = Auth::user()->id;
        });

        static::deleted(function (self $model)
        {
            $file = public_path($model->src);
            if (file_exists($file))
            {
                unlink($file);
            }
        });
    }

    /** RELATIONS */

    public function fileable()
    {
        return $this->morphTo();
    }
}
