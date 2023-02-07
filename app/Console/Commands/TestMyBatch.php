<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMyBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test'; ← #　バッチの名前を書く

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
	 for ($i = 0; $i < 5; $i++) {
		 echo "Hello✋";　　　　　　　←実行する処理を記載この場合Helloが5回出力される。
	 } 
        return 0;
    }
}
