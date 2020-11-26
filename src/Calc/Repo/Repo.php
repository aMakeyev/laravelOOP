<?php namespace Calc\Repo;

use Illuminate\Container\Container;

abstract class Repo
{
    /** @var string Model class name */
    protected $modelClassName;
    /** @var \Calc\Model\BaseModel */
    protected $model;

    public function __construct(Container $app)
    {
        $this->model = $app->make($this->modelClassName);
    }

    public function newEmpty()
    {
        $obj = new $this->modelClassName;

        return $obj;
    }

    /**
     * Create record
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update record by ID
     *
     * @param null $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function update($id = null, array $attributes)
    {
        $obj = $this->find($id);

        return ! $obj->update($attributes) ? false : $obj;
    }

    /**
     * Get all records
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * Find record by ID
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Delete many records by IDs
     *
     * @param array|int $ids
     *
     * @return int Total deleted records
     */
    public function destroy($ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * Delete record by ID
     *
     * @param int $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Duplicate record by ID
     *
     * @param int $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function duplicate($id)
    {
        return $this->find($id)->replicate()->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query()
    {
        return $this->model->query()->select($this->model->getTable() . '.*');
    }
}
