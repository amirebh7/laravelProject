<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\ActiveCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Notifications\ActiveCode as ActiveCodeNotification;

class TwoFactorAuthController extends Controller
{
    public function manageTwoFactor()
    {
        return view('profile.two-factor-auth');
    }

    public function postManageTwoFactor(Request $request)
    {
        $data = $this->validateRequestData($request);

        if($this->isRequestTypeSms($data)) {
            if($request->user()->phone_number !== $data['phone']) {
                return $this->createCodeAndSendSms($request, $data);
            } else {
                $request->user()->update([
                    'two_factor_type' => 'sms'
                ]);
            }

        }

        if($this->isRequestTypeOff($data)) {
            $request->user()->update([
                'two_factor_type' => 'off'
            ]);
        }
        return back();
    }
    private function validateRequestData(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:sms,off',
//            'phone' => 'required_unless:type,off|unique:users,phone_number',
            'phone' => ['required_unless:type,off' , Rule::unique('users' , 'phone_number')->ignore($request->user()->id)]
        ]);
        return $data;
    }

    private function createCodeAndSendSms(Request $request, $data)
    {
        $request->session()->flash('phone' , $data['phone']);

        // create a new code
        $code=ActiveCode::generateCode($request->user());

        // send the code to user phone number
        //TODO Send Sms
        $request->user()->notify(new ActiveCodeNotification($code, $data['phone']));
//                    return $code;  // code sakhte shode ra be ma neshan midahad
        return redirect(route('profile.2fa.phone'));
    }

    private function isRequestTypeSms($data): bool
    {
        return $data['type'] === 'sms';
    }
    private function isRequestTypeOff($data): bool
    {
        return $data['type'] === 'off';
    }
}
