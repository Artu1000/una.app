<?php

namespace App\Console\Commands\Install;

use Illuminate\Console\Command;

class SymLinksPrepare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'symlinks:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the symlinks that allow user to access to storage folders and files';

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
    public function handle()
    {
        $this->line(' ');

        $this->line('Preparing symlinks ...');

        // we remove all the symlinks found into the public/img folder
        $this->line('Removing existing symlinks ...');
        $links = [];
        foreach (scandir(public_path('img')) as $item) {
            if (is_link($link = public_path('img/' . $item))) {
                $links[] = $link;
                unlink($link);
            }
        }
        $this->info('✔ Existing symlinks removed :');
        foreach ($links as $link) {
            $this->line('- ' . $link);
        }

        // we prepare the symlinks we want to add
        $symlinks = [
            // logo
            [
                'storage' => config('image.settings.storage_path'),
                'public'  => public_path(config('image.settings.public_path')),
            ],
            // news
            [
                'storage' => app(\App\Repositories\News\NewsRepositoryInterface::class)
                    ->getModel()
                    ->storagePath(),
                'public'  => app(\App\Repositories\News\NewsRepositoryInterface::class)
                    ->getModel()
                    ->publicPath(),
            ],
            // partners
            [
                'storage' => app(\App\Repositories\Partner\PartnerRepositoryInterface::class)
                    ->getModel()
                    ->storagePath(),
                'public'  => app(\App\Repositories\Partner\PartnerRepositoryInterface::class)
                    ->getModel()
                    ->publicPath(),
            ],
            // slides
            [
                'storage' => app(\App\Repositories\Slide\SlideRepositoryInterface::class)
                    ->getModel()
                    ->storagePath(),
                'public'  => app(\App\Repositories\Slide\SlideRepositoryInterface::class)
                    ->getModel()
                    ->publicPath(),
            ],
            // users
            [
                'storage' => \Sentinel::getUserRepository()
                    ->createModel()
                    ->storagePath(),
                'public'  => \Sentinel::getUserRepository()
                    ->createModel()
                    ->publicPath(),
            ],
        ];

        // we create the symlinks that are not already are operational
        $links = [];
        foreach ($symlinks as $symlink) {
            if (!is_link($symlink['public'])) {
                $command = 'ln -s ' . $symlink['storage'] . ' ' . $symlink['public'];
                \Console::execWithOutput($command, $this);
                $links[] = $symlink['public'];
            }
        }
        if (!empty($links)) {
            $this->info('✔ New symlinks created :');
            foreach ($links as $link) {
                $this->line('- ' . $link);
            }
        }

        $this->info('✔ Symlinks are ready' . PHP_EOL);
    }
}
