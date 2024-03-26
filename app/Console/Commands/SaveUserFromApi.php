<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SaveUserFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch user from API and store data in database and csv file in storage/public/file/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::post('http://127.0.0.1:8000/api/users/store', []);
        if ($response->successful()) {
            Log::info('Store API successfully called');
        } else {
            Log::info('Failed to call Store API');
        }
        return 0;
    }
}
