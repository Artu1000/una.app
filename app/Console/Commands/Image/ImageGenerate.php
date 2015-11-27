<?php

namespace App\Console\Commands\Image;

use Illuminate\Console\Command;

class ImageGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:generate {model} {image_column}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all images for a given model. The model argument must be given as a string => for example : "\App\Models\Users"';

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

        $model = $this->argument('model');

        // we check if the given model
        if (!is_a($entity = new $model(), \Illuminate\Database\Eloquent\Model::class)) {
            $this->error('The given model isn\'t an instance of \Illuminate\Database\Eloquent\Model.');
        }

        $column = $this->argument('image_column');
        if (!in_array($column, $entity->getFillable())) {
            $this->error("The given column name isn't an existent model column");
        }

        $this->info(' ');
        $this->line('Optimzation and resize of the ' . $model . ' model images started ...');

        $entities = $entity->whereRaw('LENGTH(' . $column . ') > 0')->get();

        if ($total = count($entities)) {
            $bar = $this->output->createProgressBar($total);
            foreach ($entities as $object) {

                // we get the image name and extension
                list($file_name, $extension) = explode('.', $object->getAttribute($column));

                \ImageManager::optimizeAndResize(
                    $object->storagePath() . '/' . $file_name . '.' . $extension,
                    $file_name,
                    $extension,
                    $object->storagePath(),
                    $object->availableSizes(),
                    false
                );

                // we advance the progress bar
                $bar->advance();
            }

            // we finish the progress bar
            $bar->finish();

            $this->info(' ');
            $this->info('✔ Images optimization and resize of the ' . $model . ' model complete.');
            $this->info(' ');
        } else {
            $this->success("No database line with associated image were found.");
        }
    }
}
