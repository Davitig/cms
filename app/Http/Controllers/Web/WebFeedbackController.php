<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\FeedbackRequest;
use App\Models\Page\PageFile;
use App\Models\Setting\WebSetting;
use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;

class WebFeedbackController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(protected Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $pages)
    {
        $data['current'] = $page = last($pages);

        $data['files'] = (new PageFile)->getFiles($page->id);

        return view('web.feedback', $data);
    }

    /**
     * Send an e-mail.
     *
     * @param  \App\Http\Requests\Web\FeedbackRequest  $request
     * @param  \Illuminate\Contracts\Mail\Mailer  $mail
     * @param  array  $pages
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(FeedbackRequest $request, Mailer $mail, array $pages)
    {
        $email = (new WebSetting)->findByName('email')->value;

        $data = $request->all(['name', 'email', 'phone', 'text']);

        $subject = $request->getHost() . ' - feedback';

        try {
            $mail->send('web.mail.feedback', $data, function ($m) use ($data, $email, $subject) {
                $m->from(config('mail.username'), $this->request->getHost())
                  ->to($email)
                  ->subject($subject);
            });

            $message = fill_data(true, trans('send_success'));
        } catch (Exception) {
            $message = fill_data(false, trans('send_failure'));
        }

        return back()->with('alert', $message);
    }
}
