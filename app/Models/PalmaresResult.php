<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PalmaresResult extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'palmares_results';

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
        'palmares_event_id',
        'boat',
        'position',
        'crew'
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

    public function event()
    {
        return $this->belongsTo('App\Models\PalmaresEvent', 'palmares_event_id', 'id');
    }
}
