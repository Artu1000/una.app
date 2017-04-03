<?php

namespace App\Console\Commands\Images;

use App\Models\HomeSlide;
use App\Models\LibraryImage;
use App\Models\News;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Photo;
use App\Models\Slide;
use App\Models\User;
use App\Models\Video;
use Console;
use CustomLog;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Image;
use ImageManager;

class ReGenerateImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:regenerate';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate and recompress the project images';
    
    /**
     * MailcatcherInstall constructor.
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
        $images = [
            // settings
            [
                'entity'       => storage_path('app/settings/settings.json'),
                'field'        => 'logo_light',
                'file_name'    => config('image.settings.logo.name.light'),
                'extension'    => config('image.settings.logo.extension'),
                'storage_path' => config('image.settings.storage_path'),
                'sizes'        => config('image.settings.logo.sizes'),
            ],
            [
                'entity'       => storage_path('app/settings/settings.json'),
                'field'        => 'logo_dark',
                'file_name'    => config('image.settings.logo.name.dark'),
                'extension'    => config('image.settings.logo.extension'),
                'storage_path' => config('image.settings.storage_path'),
                'sizes'        => config('image.settings.logo.sizes'),
            ],
            // users
            [
                'entity' => new User(),
                'field'  => 'photo',
            ],
            // home slides
            [
                'entity' => new Slide(),
                'field'  => 'background_image',
            ],
            // news
            [
                'entity' => new News(),
                'field'  => 'image',
            ],
            // page
            [
                'entity' => new Page(),
                'field'  => 'image',
            ],
            // library images
            [
                'entity' => new LibraryImage(),
                'field'  => 'src',
            ],
            // partner
            [
                'entity' => new Partner(),
                'field'  => 'logo',
            ],
            // photo
            [
                'entity' => new Photo(),
                'field'  => 'cover',
            ],
            // video
            [
                'entity' => new Video(),
                'field'  => 'cover',
            ],
        ];
        
        // images regeneration treatment
        foreach ($images as $img) {
            
            // we get the entity
            $entity = null;
            $inputs = [];
            $is_json = false;
            
            if (is_a($entity = $img['entity'], Model::class)) {
                // we check the model field
                if (!in_array($img['field'], $entity->getFillable())) {
                    $this->error('The given ' . $img['field'] . ' column name is not an existent model column for the model ' . $img['entity']);
                }
            } else if (file_exists($img['entity'])) {
                $entity = json_decode(file_get_contents($img['entity']));
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->error('The given ' . $img['entity'] . ' file is not a valid JSON');
                }
                // we get the current json values
                foreach ($entity as $key => $value) {
                    if (!isset($inputs[$key])) {
                        $inputs[$key] = $value;
                    }
                }
                $is_json = true;
            } else {
                $this->error('Unrecognized ' . $img['entity'] . ' entity type');
            }
            
            // we all entities
            $entities = new Collection();
            if ($is_json) {
                $entities->add($entity);
            } else {
                $entities = $entity->all();
            }
            
            // we get the storage path
            $storage_path = isset($img['storage_path']) ? $img['storage_path'] : $entity->storagePath();
            // we get the sizes
            $sizes = isset($img['sizes']) ? $img['sizes'] : $entity->availableSizes($img['field']);
            // we get the image name
            $image_name = isset($img['file_name']) ? $img['file_name'] : $entity->imageName($img['field']);
            
            // we regenerate images for all entities
            $file_name = null;
            foreach ($entities as $entity) {
                
                // if an image exists
                if ($old_file_name = $entity->{$img['field']}) {
                    
                    // we notify the entity name
                    $entity_name = $is_json ? $img['entity'] : get_class($entity);
                    $this->line('=> ' . $entity_name);
                    
                    // we get the image
                    try {
                        $image = Image::make($storage_path . '/' . $old_file_name);
                        
                        // we compress and resize the image
                        $new_file_name = ImageManager::storeResizeAndRename(
                            $image->basePath(),
                            $image_name,
                            $image->extension,
                            $storage_path,
                            $sizes,
                            false // we do not remove the src image
                        );
                        
                        // we delete all previous images
                        $removed = ImageManager::remove(
                            $old_file_name,
                            $storage_path,
                            $sizes
                        );
                        if ($removed) {
                            $this->info('✔ ' . $image->basePath() . ' removed.');
                        }
                        
                        // we store the new image name
                        $inputs[$img['field']] = $new_file_name;
                        
                        // we save the new image name
                        if ($is_json) {
                            file_put_contents($img['entity'], json_encode($inputs));
                        } else {
                            $entity->update($inputs);
                            $entity->save();
                        }
                        
                        // we notify the regeneration success
                        $this->info('✔ ' . $storage_path . '/' . $new_file_name . ' generated.' . PHP_EOL);
                    } catch (Exception $e) {
                        // we log the error
                        CustomLog::error($e);
                        
                        // we show the error message
                        $this->error($e->getMessage());
                    }
                }
            }
        }
    }
}
