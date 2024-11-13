<?php

namespace App\Console\Commands;

use App\ModelFactories\NewsCategoryModelFactory;
use Illuminate\Console\Command;

class NewsCategoryAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:news-category:add {title} {slug?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add news category for future articles.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new NewsCategoryModelFactory())($this->arguments())->save();
    }
}
