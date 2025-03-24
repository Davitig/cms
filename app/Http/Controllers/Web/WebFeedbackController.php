<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\FeedbackRequest;
use App\Mail\FeedbackSubmitted;
use App\Models\Page\PageFile;
use Exception;
use Illuminate\Mail\MailManager;

class WebFeedbackController extends Controller
{

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
     * Send the message.
     *
     * @param  \App\Http\Requests\Web\FeedbackRequest  $request
     * @param  \Illuminate\Mail\MailManager  $mail
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(FeedbackRequest $request, MailManager $mail)
    {
        try {
            $mail->send(new FeedbackSubmitted($request->validated()));

            $message = fill_data(true, trans('general.sent'));
        } catch (Exception) {
            $message = fill_data(false, trans('general.send_failure'));
        }

        return back()->with('alert', $message);
    }
}
