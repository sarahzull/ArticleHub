<?php

namespace App\Services;

/**
 * Class WebhookService
 * @package App\Services
 */
class WebhookService
{
    public static function userValidation ($request) 
    {
      $userData = $request->input('user');

        if (isset($userData['id'])) {
            $exist = UserService::checkUserExists($userData['id']);

            if ($exist) {
                return response()->json([
                    'code' => "200",
                    'message' => 'user exists.'
                ], 200);
            } else {
                return response()->json([
                    'error' => [
                        'code' => "INVALID_USER",
                        'message' => 'Invalid user'
                    ]
                ], 400);
            }
        }

        return response()->json([
            'error' => [
                'code' => "INVALID_PARAMETER",
                'message' => 'Invalid parameter'
            ]
        ], 400);
    }
}
