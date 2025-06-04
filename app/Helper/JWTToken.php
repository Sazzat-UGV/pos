<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    public static function createToken($userEmail)
    {
        $key     = env('JWT_KEY');
        $payload = [
            'iss'       => 'laravel-token',
            'iat'       => time(),
            'exp'       => time() + 60 * 60 * 24 * 30,
            'userEmail' => $userEmail,
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    // public static function CreateTokenForSetPassword($userEmail)
    // {
    //     $key = env('JWT_KEY');
    //     $payload = [
    //         'iss' => 'laravel-token',
    //         'iat' => time(),
    //         'exp' => time() + (60 * 20), // 20 minutes
    //         'userEmail' => $userEmail,
    //         'userID' => 0,
    //     ];

    //     return JWT::encode($payload, $key, 'HS256');
    // }

    public static function verifyToken($token)
    {
        try {
            $key    = env('JWT_KEY');
            $decode = JWT::decode($token, new Key($key, 'HS256'));
            return [
                'status' => true,
                'data'   => $decode,
            ];
        } catch (Exception $e) {
            return [
                'status'  => false,
                'message' => 'Invalid or expired token',
            ];
        }
    }
}
