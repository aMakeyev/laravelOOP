<?php namespace Calc\Controller;

use Calc\Model\Client;
use Calc\Core\Controllers\BaseController;
use Calc\Model\Sendmail;
use Calc\Repo\SendmailRepo;
use Mail;

class SendmailsController extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->title->prepend(trans('calc::titles.sendmails'));
    }

    public function index()
    {
        $this->layout->content = view('calc::sendmails.index');
    }

    public function show($id)
    {
        /** @var Sendmail $obj */
        $obj = Sendmail::findOrFail($id);
        $this->layout->content = view('calc::sendmails.show')->with('obj', $obj);
    }

	public function testEmail()
	{
		$user = \Auth::user();
		Mail::send('calc::emails.testemail', compact('user'), function ($message) use ($user)
		{
			$message->to($user->email, $user->present()->fullName);
			$message->subject('Тестовое письмо');
		});
	}
}
