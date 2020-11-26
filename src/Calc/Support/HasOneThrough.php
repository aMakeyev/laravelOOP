<?php namespace Calc\Support;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HasOneThrough extends HasManyThrough
{
    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->get();
    }
    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = array('*'))
    {
        // First we'll add the proper select columns onto the query so it is run with
        // the proper columns. Then, we will get the results and hydrate out pivot
        // models with the result of those columns as a separate model relation.
        $select = $this->getSelectColumns($columns);

        $models = $this->query->addSelect($select)->getModels();

        return $models->first();
    }
}
