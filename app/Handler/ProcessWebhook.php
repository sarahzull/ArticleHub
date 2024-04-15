<?php

namespace App\Handler;

use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

//The class extends "ProcessWebhookJob" class as that is the class 
//that will handle the job of processing our webhook before we have 
//access to it.

class ProcessWebhook extends ProcessWebhookJob
{
    public function handle()
    {
        $response = json_decode($this->webhookCall, true);
        $data = $response['payload'];
        $type = $data['notification_type'];
    
        if ($type == 'user_validation') {
          $userData = $data['user'];

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
                          'code' => "400",
                          'message' => 'user not found.'
                      ]
                  ], 400);
              }
          }

          return response()->json([
              'error' => [
                  'code' => "400",
                  'message' => 'user id is required.'
              ]
          ], 400);

        } elseif ($type == 'created_subscription') {

        } elseif ($type == 'canceled_subscription') {

        }

        http_response_code(200);
    }
}