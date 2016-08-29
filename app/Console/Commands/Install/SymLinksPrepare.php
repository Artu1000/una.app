<?php

namespace App\Console\Commands\Install;

use App\Repositories\Libraries\LibraryFileRepositoryInterface;
use App\Repositories\Libraries\LibraryImageRepositoryInterface;
use App\Repositories\Media\PhotoRepositoryInterface;
use App\Repositories\Media\VideoRepositoryInterface;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Page\PageRepositoryInterface;
use App\Repositories\Partner\PartnerRepositoryInterface;
use App\Repositories\Slide\SlideRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
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
     * SymLinksPrepare constructor.
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
        // we remove all the symlinks found into the public folder
        $links = [];
        foreach (scandir(public_path()) as $item) {
            if (is_link($link = public_path($item))) {
                $links[] = $link;
                unlink($link);
            }
        }
        foreach (scandir(public_path('img')) as $item) {
            if (is_link($link = public_path('img/' . $item))) {
                $links[] = $link;
                unlink($link);
            }
        }
        $this->info('► Existing symlinks removed :');
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
            // schedules page
            [
                'storage' => config('image.schedules.storage_path'),
                'public'  => public_path(config('image.schedules.public_path')),
            ],
            // registration page
            [
                'storage' => config('image.registration.storage_path'),
                'public'  => public_path(config('image.registration.public_path')),
            ],
            // news
            [
                'storage' => app(NewsRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(NewsRepositoryInterface::class)->getModel()->publicPath(),
            ],
            // partners
            [
                'storage' => app(PartnerRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(PartnerRepositoryInterface::class)->getModel()->publicPath(),
            ],
            // pages
            [
                'storage' => app(PageRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(PageRepositoryInterface::class)->getModel()->publicPath(),
            ],
            // slides
            [
                'storage' => app(SlideRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(SlideRepositoryInterface::class)->getModel()->publicPath(),
            ],
            // users
            [
                'storage' => app(UserRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(UserRepositoryInterface::class)->getModel()->publicPath(),
            ],
            // photos
            [
                'storage' => app(PhotoRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(PhotoRepositoryInterface::class)->getModel()->publicPath(),
            ],
            // videos
            [
                'storage' => app(VideoRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(VideoRepositoryInterface::class)->getModel()->publicPath(),
            ],
            // images library
            [
                'storage' => app(LibraryImageRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(LibraryImageRepositoryInterface::class)->getModel()->publicPath(),
            ],
            // files library
            [
                'storage' => app(LibraryFileRepositoryInterface::class)->getModel()->storagePath(),
                'public'  => app(LibraryFileRepositoryInterface::class)->getModel()->publicPath(),
            ],
        ];

        /// we create the symlinks that are not already are operational
        $this->info('► New symlinks created :');
        foreach ($symlinks as $symlink) {
            if (!is_link($symlink['public'])) {
                $command = 'ln -s ' . $symlink['storage'] . ' ' . $symlink['public'];
                exec($command);
                $this->line('- ' . $symlink['public']);
            }
        }
    }
}
