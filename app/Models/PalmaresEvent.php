<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalmaresEvent extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'palmares_events';

    /**
     * The attributes that are not assignable.
     *
     * @var string
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'location',
        'date'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * relations
     */

    public function category()
    {
        return config('palmares.category.' . $this->category_id);
    }

    public function results()
    {
        return $this->hasMany('App\Models\PalmaresResult', 'palmares_event_id', 'id')->orderBy('position');
    }
}
