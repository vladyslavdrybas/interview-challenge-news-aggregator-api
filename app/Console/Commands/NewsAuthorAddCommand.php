<?php

namespace App\Console\Commands;

use App\ModelFactories\NewsAuthorModelFactory;
use Illuminate\Console\Command;

class NewsAuthorAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:news-author:add {full_name} {slug?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add news author.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new NewsAuthorModelFactory())($this->arguments())->save();
    }
}
