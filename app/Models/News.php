<?php

namespace App\Models;

class News extends _BaseModel
{
    /**
     * News constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // we set the object attributes
        $this->attributes = $attributes;

        // we define the table name
        $this->table = 'news';

        // we define the fillable attributes
        $this->fillable = [
            'author_id',
            'category_id',
            'photo_album_id',
            'video_id',
            'key',
            'title',
            'image',
            'meta_title',
            'meta_description',
            'meta_keyword',
            'content',
            'released_at',
            'active',
        ];

        // we define the image(s) size(s)
        $this->sizes = [
            'image' => [
                'admin'       => [40, 40],
                'list'        => [150, 150],
                'list_mobile' => [766, 200],
                '767'         => [767, 431],
                '991'         => [991, 557],
                '1199'        => [1199, 674],
                '1919'        => [1919, 1079],
                '2560'        => [2560, 1440],
            ],
        ];

        // we define the public path to retrieve files
        $this->public_path = 'img/news';

        // we define the storage path to store files
        $this->storage_path = 'app/news';
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function photoAlbum()
    {
        return $this->hasOne(Photo::class, 'id', 'photo_album_id');
    }

    public function video()
    {
        return $this->hasOne(Video::class, 'id', 'video_id');
    }
}
