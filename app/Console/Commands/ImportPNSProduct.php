<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PnsController;

class ImportPNSProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-p-n-s-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import PNS product to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $pnsController = new PnsController();
        $pnsController->index();
    }
}
