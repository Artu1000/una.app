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

        // we prepare the symlinks we want to prepare
        $symlinks = [
//            [
//                'storage' => 'app/home/slides',
//                'public'  => 'img/slides',
//            ],
            [
                'storage' => app()->make(\App\Repositories\Partner\PartnerRepositoryInterface::class)
                    ->getModel()
                    ->storagePath(),
                'public'  => app()->make(\App\Repositories\Partner\PartnerRepositoryInterface::class)
                    ->getModel()
                    ->publicPath(),
            ],
        ];

        // we create the symlinks that are not already are operationnals
        foreach ($symlinks as $symlink) {
            if (!is_link($symlink['public'])) {
                $command = 'ln -s ' . $symlink['storage'] . ' ' . $symlink['public'];
                \Console::execWithOutput($command, $this);
                $this->info('✔ "' . $symlink['public'] . '" symlink created');
            }
        }

        $this->info('✔ Symlinks operational');

        $this->line(' ');
    }
}
