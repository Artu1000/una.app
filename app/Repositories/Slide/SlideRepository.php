<?php

namespace App\Repositories\Slide;

use App\Models\Slide;
use App\Repositories\BaseRepository;

class SlideRepository extends BaseRepository implements SlideRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Slide();
    }

    public function sanitizePositions()
    {
        $slides_data = $this->model->selectRaw('MAX(position) as max')
            ->selectRaw('COUNT(*) as count')
            ->first();

        // if we detect a position gap
        if ($slides_data->max > $slides_data->count) {
            // we correct the position of all slides
            $slides = $this->model->orderBy('position', 'asc')->get();

            $verification_position = 0;
            foreach ($slides as $s) {
                // we update the incorrect ranks
                if ($s->position !== $verification_position + 1) {
                    $s->position = $verification_position + 1;
                    $s->save();
                }
                // we increment the verification position
                $verification_position++;
            }
        }
    }

    /**
     * @param int $parent_role_id
     * @return int
     */
    public function updatePositions($previous_slide_id)
    {

        // we get the roles concerned by the position incrementation regarding the given previous slide
        if ($previous_slide = $this->model->find($previous_slide_id)) {
            // if a parent is defined
            // we get the roles hierarchically inferiors to the parent
            $slides = $this->model->where('position', '>', $previous_slide->position)
                ->orderBy('position', 'desc')
                ->get();
        } else {
            // if the role has to be the master role
            // we get all roles
            $slides = $this->model->orderBy('position', 'desc')->get();
        }

        // we increment the position of the selected slides
        foreach ($slides as $s) {
            $s->position += 1;
            $s->save();
        }

        // we get the new position to apply to the current slide
        $new_position = $previous_slide ? ($previous_slide->position + 1) : 1;

        return $new_position;
    }
}