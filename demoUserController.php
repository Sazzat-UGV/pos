<?php
namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{


    public function userLogout(Request $request)
    {
        return redirect('/')->cookie('token', '', -1);
    }
    public function sendOTPCode(Request $request)
    {$request->validate([
        'email' => 'required|email|max:50|exists:users,email',
    ]);
        $email = $request->input('email');
        $otp   = rand(1000, 9000);
        $count = User::where('email', $email)->count();
        if ($count == 1) {
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', $email)->update(
                [
                    'otp' => $otp,
                ]
            );
            return response()->json([
                'status'  => 'success',
                'message' => '4 Digit ' . $otp . ' Code has been send to your email !',
            ], 200);
        } else {
            return response()->json([
                'status'  => 'failed',
                'message' => 'Unauthorized',
            ]);

        }}
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50|exists:users,email',
            'otp'   => 'required',
        ]);
        $email = $request->input('email');
        $otp   = $request->input('otp');
        $count = User::where('email', $email)->where('otp', $otp)->count();
        if ($count == 1) {
            User::where('email', $email)->where('otp', $otp)->update([
                'otp' => null,
            ]);
            $token = JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status'  => 'success',
                'message' => 'OTP Verification Sucessful.',
                'token'   => $token,
            ], 200)->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status'  => 'failed',
                'message' => 'Unauthorized',
            ]);
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|same:c_password',
        ]);
        try {
            $email    = $request->header('email');
            $password = $request->input('password');
            User::where('email', $email)->update([
                'password' => Hash::make($password),
            ]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Request Successful',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'failed',
                'message' => 'Something went wrong',
            ], 200);
        }
    }
    public function userProfile(Request $request)
    {
        $email = $request->header('email');
        $user  = User::where('email', $email)->first();
        return response()->json([
            'status'  => 'success',
            'message' => 'Report Successful',
            'data'    => $user,
        ], 200);
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:50',
            'email'    => 'required|email|max:50|unique:users,email',
            'phone'    => 'required|string|max:50',
            'password' => 'sometimes|string|min:4',
        ]);
        try {
            $email    = $request->header('email');
            $name     = $request->input('name');
            $mobile   = $request->input('mobile');
            $password = $request->input('password');
            User::where('email', $email)->update([
                'name'     => $name,
                'mobile'   => $mobile,
                'password' => $password,
            ]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Request Succesful',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'failed',
                'message' => 'Something went wrong',
            ], 200);
        }
    }
}
