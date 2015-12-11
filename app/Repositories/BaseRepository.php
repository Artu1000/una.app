<?php

namespace App\Repositories;


use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The repository model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The query builder
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Alias for the query limit
     *
     * @var int
     */
    protected $take;

    /**
     * Alias for the query limit
     *
     * @var int
     */
    protected $skip;

    /**
     * Array of related models to eager load
     *
     * @var array
     */
    protected $with = [];

    /**
     * Array of one or more where clause parameters
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * Array of one or more where in clause parameters
     *
     * @var array
     */
    protected $whereIns = [];

    /**
     * Array of one or more ORDER BY column/value pairs
     *
     * @var array
     */
    protected $orderBys = [];

    /**
     * Array of scope methods to call on the model
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * Get the model from the IoC container
     */
    public function __construct()
    {
        $this->model = app()->make($this->model);
    }

    /**
     * Get the repository model to access to its methods
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get all the model records in the database
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        $this->newQuery()->eagerLoad();
        $models = $this->query->get();
        $this->unsetClauses();

        return $models;
    }

    /**
     * Count the number of specified model records in the database
     *
     * @return int
     */
    public function count()
    {
        return $this->get()->count();
    }

    /**
     * Create a new model record in the database
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $this->unsetClauses();

        return $this->model->create($data);
    }

    /**
     * Create one or more new model records in the database
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createMultiple(array $data)
    {
        $models = new Collection();
        foreach ($data as $d) {
            $models->push($this->create($d));
        }

        return $models;
    }

    /**
     * Delete one or more model records from the database
     *
     * @return mixed
     */
    public function delete()
    {
        $this->newQuery()->setClauses()->setScopes();
        $result = $this->query->delete();
        $this->unsetClauses();

        return $result;
    }

    /**
     * Delete the specified model record from the database
     *
     * @param $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteById($id)
    {
        $this->unsetClauses();

        return $this->find($id)->delete();
    }

    /**
     * Delete multiple records
     *
     * @param array $ids
     *
     * @return int
     */
    public function deleteMultipleById(array $ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * Get the first specified model record from the database
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $model = $this->query->firstOrFail();
        $this->unsetClauses();

        return $model;
    }

    /**
     * Get all the specified model records in the database
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $models = $this->query->get();
        $this->unsetClauses();

        return $models;
    }

    /**
     * Get the specified model record from the database
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        $this->unsetClauses();
        $this->newQuery()->eagerLoad();

        return $this->query->findOrFail($id);
    }

    /**
     * Get the specified model record from the database from its attribute
     *
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value)
    {
        return $this->model->where($attribute, '=', $value)->first();
    }

    /**
     * Set the query limit
     *
     * @param int $limit
     *
     * @return $this
     */
    public function take($limit)
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * Set the query skip
     *
     * @param int $skip
     *
     * @return $this
     */
    public function skip($start)
    {
        $this->skip = $start;

        return $this;
    }

    /**
     * Set an ORDER BY clause
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * Update the specified model record in the database
     *
     * @param       $id
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateById($id, array $data)
    {
        $this->unsetClauses();
        $model = $this->find($id);
        $model->update($data);

        return $model;
    }

    /**
     * @param $column
     * @param $operator
     * @param null $value
     * @return $this
     */
    public function where($column, $operator, $value = null)
    {
        if(!isset($value)){
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = compact('column', 'operator', 'value');

        return $this;
    }

    /**
     * Add a simple where in clause to the query
     *
     * @param string $column
     * @param mixed $values
     *
     * @return $this
     */
    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];
        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Set Eloquent relationships to eager load
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        if (is_string($relations)) $relations = func_get_args();
        $this->with = $relations;

        return $this;
    }

    /**
     * Create a new instance of the model's query builder
     *
     * @return $this
     */
    protected function newQuery()
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Add relationships to the query builder to eager load
     *
     * @return $this
     */
    protected function eagerLoad()
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }

    /**
     * Set clauses on the query builder
     *
     * @return $this
     */
    protected function setClauses()
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }
        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }
        if (isset($this->take) and !is_null($this->take)) {
            $this->query->take($this->take);
        }
        if (isset($this->skip) and !is_null($this->skip)) {
            $this->query->skip($this->skip);
        }
        if (isset($this->paginate) and !is_null($this->paginate)) {
            $this->query->paginate($this->paginate);
        }

        return $this;
    }

    /**
     * Set query scopes
     *
     * @return $this
     */
    protected function setScopes()
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    /**
     * Reset the query clause parameter arrays
     *
     * @return $this
     */
    protected function unsetClauses()
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }

    /**
     * Get paginated list
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $models = $this->query->paginate($perPage, $columns);
        $this->unsetClauses();

        return $models;
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
     * @param $previous_slide_id
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