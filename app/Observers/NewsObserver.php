<?php

namespace App\Observers;
use App\Models\News;
use CustomLog;

/**
 * Observes the Users model
 */
class NewsObserver
{
    /**
     * Function will be triggered before a news is created
    *
    * @param News $model
    */
    public function creating(News $model)
    {
        //
    }

    /**
     * Function will be triggered after a news is created
     *
     * @param News $model
     */
    public function created(News $model)
    {
        //
    }

    /**
     * Function will be triggered before a news is updated
     *
     * @param News $model
     */
    public function updating(News $model)
    {
        //
    }

    /**
     * Function will be triggered after a news is updated
     *
     * @param News $model
     */
    public function updated(News $model)
    {
        //
    }

    /**
     * Function will be triggered before a news is saved (created or updated)
     *
     * @param News $model
     */
    public function saving(News $model)
    {
        //
    }

    /**
     * Function will be triggered after a news is saved (created or updated)
     *
     * @param News $model
     */
    public function saved(News $model)
    {
        //
    }

}