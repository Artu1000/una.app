<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EShopArticle extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'e_shop_articles';

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
        'order_type_id',
        'size_s',
        'size_m',
        'size_l',
        'size_xl',
        'size_xxl',
        'title',
        'description',
        'available_sizes',
        'price',
        'photo_1',
        'photo_2',
        'photo_3'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
