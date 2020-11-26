<?php namespace Calc\Repo;

use Mail;

class UserRepo extends Repo
{
    protected $modelClassName = 'Calc\Model\User';

    public function findByEmail($email, $columns = ['*'])
    {
        return $this->query()->where('email', $email)->get($columns);
    }

    public function findByUsername($username, $columns = ['*'])
    {
        return $this->query()->where('username', $username)->get($columns);
    }

    public function paginate(array $data = [])
    {
        $q = $this->query()->with('countCalculations');

        $q->sort(array_get($data, 'sort'), array_get($data, 'order'));
        $q->status(array_get($data, 'status'));
        $q->role(array_get($data, 'role'));
        $q->withoutAdmin();

        $paginator = $q->paginate((int) array_get($data, 'rows'));

        return $paginator;
    }

    /**
     * Отправка письма созданому менеджеру с данными для входа
     *
     * @param \Calc\Model\User $user
     */
    public function sendEmailToNewUser($user)
    {
        Mail::send('calc::emails.new_manager', compact('user'), function ($message) use ($user)
        {
            $message->to($user->email, $user->present()->fullName);
            $message->subject('Для вас была создана учетная запись');
        });
    }
}
