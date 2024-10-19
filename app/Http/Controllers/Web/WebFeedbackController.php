<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\FeedbackRequest;
use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use App\Models\Page;

class WebFeedbackController extends Controller
{
    /**
     * The Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Page $page)
    {
        $data['current'] = $page;

        $data['files'] = $page->getFiles();

        return view('web.' . $page->type, $data);
    }

    /**
     * Send an e-mail.
     *
     * @param  \Illuminate\Contracts\Mail\Mailer  $mail
     * @param  \App\Http\Requests\Web\FeedbackRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Mailer $mail, FeedbackRequest $request)
    {
        $webSettings = app('db')->table('web_settings')->first();

        $data = $request->all(['name', 'email', 'phone', 'text']);

        $email = $webSettings->email;

        $subject = $request->getHost() . ' - feedback';

        try {
            $mail->send('web.mail.feedback', $data, function ($m) use ($data, $email, $subject) {
                $m->from(config('mail.username'), $this->request->getHost())
                  ->to($email)
                  ->subject($subject);
            });

            $message = fill_data(true, trans('send_success'));
        } catch (Exception $e) {
            $message = fill_data(false, trans('send_failure'));
        }

        return redirect()->back()->with('alert', $message);
    }
}
