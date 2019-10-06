<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OceanService;

class OceanServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oceanService:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '海況情報取得クローラー実行';

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
     * @return mixed
     */
    public function handle(OceanService $oceanService)
    {
        \Log::info('[開始] 海況情報取得');
        $oceanService->execute();
        \Log::info('[終了] 海況情報取得');
    }
}
