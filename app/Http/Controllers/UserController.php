<?php
namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userRegistration(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email',
            'mobile'     => 'required|string|max:20',
            'password'   => 'required|string|min:4',
        ]);
        try {
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name'),
                'email'      => $request->input('email'),
                'mobile'     => $request->input('mobile'),
                'password'   => Hash::make($request->input('password')),
            ]);
            return response()->json([
                'status'  => true,
                'message' => 'User registered successfully.',
                'data'    => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'User registered failed.',
                'data'    => null,
            ]);
        }
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:4',
        ]);
        $credentials = [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];
        if (Auth::attempt($credentials)) {
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json([
                'status'  => true,
                'message' => 'User login successfully',
                'token'   => $token,
            ], 200)->cookie('token', $token, time() + 60 * 24 * 30);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 200);
        }
    }

    public function sendOTPCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $email = $request->input('email');
        $otp   = rand(1000, 9999);
        $count = User::where('email', $email)->count();
        if ($count == 1) {

        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ]);
        }
    }
}
