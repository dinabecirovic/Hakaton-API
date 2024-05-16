<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verify(Request $request)
    {

        $this->validate($request, [
           'id' => 'required',
           'hash' => 'required',
        ]);

        $user = User::where('id', $request->id)
            ->where('email_verification_token', $request->hash)
            ->first();

        if(!$user)
            return redirect(config('quaaant.redirect_after_email_welcome'));


        $user->email_verification_token = null;
        $user->save();

        if($request->is_welcome){

            return redirect(config('quaaant.redirect_after_email_welcome'));

        }
        else{
            
            return redirect(config('quaaant.redirect_after_email_verify'));

        }

    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if (!auth()->user()) {
            return response(null, Response::HTTP_NOT_FOUND);
        }

        if (!auth()->user()->email_verification_token) {
            return response(null, Response::HTTP_NOT_FOUND);
        }

        auth()->user()->resendVerificationEmail();

        return response()->json(null);

    }

}
