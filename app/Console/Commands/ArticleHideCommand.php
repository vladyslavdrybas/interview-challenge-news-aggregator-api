<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class ArticleHideCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:article:hide {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'You can hide the article from public view. Who knows what crap aggregator can grab from the internet :)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $article = Article::find($id);

        if (!$article) {
            $this->error('Article not found.');
            return;
        }

        if ($article->is_hidden === 0) {
            $article->is_hidden = 1;
            $article->save();

            $this->info('Article field updated successfully.');
        } else {
            $this->info('Article field is already true.');
        }
    }
}
