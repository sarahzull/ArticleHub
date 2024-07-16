<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleService;

class ProcessFiles extends Command
{
    protected $signature = 'process:files';
    protected $description = 'Process text files from Google Drive and insert data into Google Sheets';

    protected $googleService;

    public function __construct(GoogleService $googleService)
    {
        parent::__construct();
        $this->googleService = $googleService;
    }

    public function handle()
    {
        $rootFolderId = env('GOOGLE_DRIVE_FOLDER_ID');
        $spreadsheetId = env('GOOGLE_SHEETS_ID');
        $range = 'May 2024!A2';

        $folders = range(1, 31);
        $endpoints = [
            // SETTINGS
            "/api/v1/projects/[^/]+/settings/apple" => ["GET", "PATCH"],
            "/api/v1/projects/[^/]+/settings/android" => ["GET", "PATCH"],
            "/api/v1/projects/[^/]+/settings/upload" => ["POST"],

            // SUBSCRIPTION MANAGEMENT
            "/api/v1/projects/[^/]+/subscriptions" => ["POST"],
            "/api/v1/projects/[^/]+/subscriptions/[^/]+/cancel" => ["PUT"],
            "/api/v1/projects/[^/]+/subscriptions/[^/]+" => ["PUT"],
            "/api/v1/projects/[^/]+/subscriptions/[^/]+/freeze" => ["PUT"],
            "/api/v1/projects/[^/]+/subscriptions/[^/]+/unfreeze" => ["PUT"],
            "/api/v1/projects/[^/]+/subscriptions/[^/]+/scheduled_changes" => ["POST"], 

            // TWITCH
            "/api/v1/project/[^/]+/change_plan_requests/[^/]+" => ["GET"],
            "/api/v1/projects/[^/]+/change_plan_requests" => ["GET"],
            "/api/v1/project/[^/]+/change_plan_requests/[^/]+/cancel" => ["POST"],

            // API KEYS
            "/api/v1/project/[^/]+/settings/api_keys/[^/]+" => ["GET"],
            "/api/v1/project/[^/]+/settings/api_keys" => ["POST"],
            "/api/v1/project/[^/]+/settings/api_keys/[^/]+/revoke" => ["POST"],

            // CRON
            "/api/internal/v1/cron/subscription_bonuses" => ["GET"],
            "/api/internal/v1/cron/subscription_bonuses/[^/]+" => ["GET"],

            // TEST
            // "/api/user/v1/projects/[^/]+/subscriptions" => ["GET"]
        ];

        $data = [];

        foreach ($folders as $folderNumber) {
            $folderName = str_pad($folderNumber, 2, '0', STR_PAD_LEFT);

            $this->info("Checking folder: $folderName");

            $folderId = $this->googleService->getFolderIdByName($rootFolderId, $folderName);
            if (!$folderId) {
                $this->error("Folder $folderName not found.");
                continue;
            }

            $folderData = [[$folderName, '', '', '']];

            $files = $this->googleService->listFilesInFolder($folderId);

            foreach ($files as $file) {
                $this->info("Checking file: " . $file->getName());

                $content = $this->googleService->readFileContent($file->getId());

                foreach ($endpoints as $endpointPattern => $methods) {
                    foreach ($methods as $method) {
                        if ($this->checkEndpointAndMethod($content, $endpointPattern, $method)) {
                            $folderData[] = ['', $endpointPattern, $method, 'Found'];
                        } else {
                            $folderData[] = ['', $endpointPattern, $method, 'Not Found'];
                        }
                    }
                }
            }

            $data = array_merge($data, $folderData);
        }

        if (!empty($data)) {
            $this->googleService->appendToSheet($spreadsheetId, $range, $data);
        }

        $this->info('Files processed and data inserted into Google Sheets successfully.');
    }

    private function checkEndpointAndMethod($content, $endpointPattern, $method)
    {
        return preg_match("#$endpointPattern(\?.*)?$#m", $content) && stripos($content, $method) !== false;
    }
}
