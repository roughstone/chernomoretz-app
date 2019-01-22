<?php

namespace App\Http\Controllers\Mails;

use App\Mail\UserQuestions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UserQuestionController extends Controller
{
    /**
     * Send user question via email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $mail = new UserQuestions($request);

        Mail::to($request->email)->send(new UserQuestions($request));
    }
}
