<?php namespace App\Repositories\Palmares;

use App\Models\PalmaresEvent;
use App\Repositories\BaseRepository;

class PalmaresEventRepository extends BaseRepository implements PalmaresEventRepositoryInterface
{

    public function __construct()
    {
        $this->model = new PalmaresEvent();
    }

    public function eventsWithResultsSortedByCategory()
    {
        $events = $this->with('results')->get();

        $palmares = [];

        foreach(config('palmares.categories') as $id =>$category)
        {
            // we prepare the results array by palmares category
            $palmares[$id] = [
                'category' => $category,
                'events' => []
            ];
            foreach($events as $event){
                if($event->category_id === $id){
                    $palmares[$id]['events'][] = $event;
                }
            }
        }

        return $palmares;
    }
}