<?php

namespace App\Repositories;


abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The repository model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The repository model class name
     *
     * @var String
     */
    protected $modelClassName;
}