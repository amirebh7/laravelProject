<?php

namespace App\Http\Controllers\Auth;

use App\Models\ActiveCode;
use App\Notifications\LoginToWebsite as LoginToWebsiteNotification;
use Illuminate\Http\Request;
use App\Notifications\ActiveCode as ActiveCodeNotification;

trait TowFactorAuthenticate
{
    public function loggendin(Request $request , $user)
    {
        if($user->hasTwoFactorAuthenticatedEnabled()) {
            return $this->logoutAndRedirectToTokenEntry($request,$user);
        }

        $user->notify(new LoginToWebsiteNotification());
        return false;
    }


    public function logoutAndRedirectToTokenEntry(Request $request, $user)
    {
        auth()->logout();

        $request->session()->flash('auth' , [
            'user_id' => $user->id,
            'using_sms' => false,
            'remember' => $request->has('remember')
        ]);


        if($user->hasSmsTwoFactorAuthenticationEnabled()) {
            $code = ActiveCode::generateCode($user);
                $user->notify(new ActiveCodeNotification($code , $user->phone_number));
            $request->session()->push('auth.using_sms' , true);
        }

        return redirect(route('2fa.token'));
    }

}
