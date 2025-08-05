<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SearchLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search log files for specific values and show matching filenames';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //$searchDir = '/Users/sitinursarahzulkapli/downloads/sss-3727/project-notification/update-sub/17-06-2025';
        // $searchDir = '/Users/sitinursarahzulkapli/downloads/sss-3742/project notification/18-06-2025/failedChargeSubscription';
        //$searchDir = '/Users/sitinursarahzulkapli/downloads/sss-3742/autopayment/notify/16-06-2025';
        // $searchDir = '/Users/sitinursarahzulkapli/downloads/sss-3742/autopayment/collector notify/15-06-2025';
        //$searchDir = '/Users/sitinursarahzulkapli/downloads/sss-3742/billing - user notification/15-06-2025';
        //$searchDir = '/Users/sitinursarahzulkapli/downloads/sss-3742/subscription-api/15-06-2025';
        $searchDir = '/Users/sitinursarahzulkapli/downloads/sss-3742/billing - process subscription/18-06-2025';

        $patterns = [
            // 'valera.melnikov.89@mail.ru',
            // '104833015',
            // '2d947b4c-ddd2-4eb0-9053-9f5f597d1288',
            // '1674288554'
            '1703913455', //invoice id
            '98397306' //subscription id
        ];

        $files = glob($searchDir . '/*.txt');

        if (empty($files)) {
            $this->warn("no .txt files found in: $searchDir");
            return;
        }

        $matches = 0;

        foreach ($files as $file) {
            $handle = fopen($file, 'r');
            if (!$handle) {
                $this->warn("could not open file: " . basename($file));
                continue;
            }

            while (($line = fgets($handle)) !== false) {
                foreach ($patterns as $pattern) {
                    if (strpos($line, $pattern) !== false) {
                        $this->info("match found in: " . basename($file) . " (for: {$pattern})");
                        $matches++;
                        break 2; // stop reading this file after first match
                    }
                }
            }

            fclose($handle);
        }

        if ($matches === 0) {
            $this->info("no matches found.");
        }
    }

}
