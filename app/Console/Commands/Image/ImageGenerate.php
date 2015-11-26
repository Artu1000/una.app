<?php

namespace App\Console\Commands\Image;

use Approached\LaravelImageOptimizer\ImageOptimizer;
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
    protected $description = 'Generate all images for a given model.';

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

        if (!is_a($entity = new $model(), \Illuminate\Database\Eloquent\Model::class)) {
            $this->error('The given model isn\'t an instance of \Illuminate\Database\Eloquent\Model.');
        }

        $column = $this->argument('image_column');
        if (!in_array($column, $entity->getFillable())) {
            $this->error("The given column name isn't an existent model column");
        }

        $this->line('Optimzation and resize of the ' . $model . ' model images started ...');
        $this->info(' ');

        $entities = $entity->whereRaw('LENGTH(' . $column . ') > 0')->get();

        $not_existing_file = [];

        if ($total = count($entities)) {
            $bar = $this->output->createProgressBar($total);
            foreach ($entities as $object) {

                // we get the image name and extension
                list($name, $extension) = explode('.', $object->getAttribute($column));

                // we set the image path
                $path = $object->storagePath() . '/' . $name . '.' . $extension;

                if (is_file($path)) {
                    // we optimize and overwrite the original image
                    $opt = new ImageOptimizer();
                    $opt->optimizeImage($path, $extension);

                    // we save the optimized image
                    $optimized_image = \Image::make($path);
                    $optimized_image->save($path);

                    foreach ($object->availableSizes() as $key => $size) {
                        // we set the resized file name
                        $resized_file_name = $name . '_' . $key . '.' . $extension;

                        // we get the optimized original image
                        $optimized_original_image = \Image::make(
                            $object->storagePath() . '/' . $name . '.' . $extension
                        );

                        // we resize the image
                        $optimized_original_image->fit($size[0], $size[1]);
                        $resized_image_path = $object->storagePath() . '/' . $resized_file_name;
                        $optimized_original_image->save($resized_image_path);
                    }
                } else {
                    $not_existing_file[] = $path;
                }

                // we advance the progress bar
                $bar->advance();
            }

            // we finish the progress bar
            $bar->finish();

            $this->info(' ');
            $this->info(' ');
            $this->info('✔ Images optimization and resize of the ' . $model . ' model complete.');

            if (count($not_existing_file)) {
                $this->error(
                    "Please note that the following images are attached to the model but were not found in database :"
                );
                foreach ($not_existing_file as $image) {
                    $this->error(' -> ' . $image);
                }
            }
        } else {
            $this->success("No model with an associated image was found.");
        }
    }
}
