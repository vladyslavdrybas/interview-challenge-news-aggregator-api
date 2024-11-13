<?php

namespace App\Console\Commands;

use App\ModelFactories\NewsSourceModelFactory;
use Illuminate\Console\Command;

class NewsSourceAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:news-source:add {title} {base_url} {apikey} {slug?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add news source api to the list.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new NewsSourceModelFactory())($this->arguments())->save();
    }
}
