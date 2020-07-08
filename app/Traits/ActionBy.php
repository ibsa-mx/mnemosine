<?php

namespace Mnemosine\Traits;

use \Illuminate\Database\Eloquent\Builder;

/*
 * A trait to register user id on action: create, update, delete
 */

trait ActionBy
{
    /**
     * Override of delete to perform the database update of user
     *
     * @return mixed
     */
    public function delete()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());
        $columns = [$this->getDeletedByColumn() => auth()->user()->id];
        $this->{$this->getDeletedByColumn()} = auth()->user()->id;
        $query->update($columns);

        return parent::delete();
    }

    /**
     * Override of insert to perform the database update of user
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return bool
     */
    public function performInsert(Builder $query)
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());
        $columns = [$this->getCreatedByColumn() => auth()->user()->id];
        $this->{$this->getCreatedByColumn()} = auth()->user()->id;
        $query->update($columns);

        return parent::performInsert($query); 
    }

    /**
     * Override of update to perform the database update of user
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return bool
     */
    public function performUpdate(Builder $query)
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());
        $columns = [$this->getUpdatedByColumn() => auth()->user()->id];
        $this->{$this->getUpdatedByColumn()} = auth()->user()->id;
        $query->update($columns);

        return parent::performUpdate($query);
    }

    /**
     * Get the name of the "deleted by" column.
     *
     * @return string
     */
    public function getDeletedByColumn()
    {
        return defined('static::DELETED_BY') ? static::DELETED_BY : 'deleted_by';
    }

    /**
     * Get the name of the "updated by" column.
     *
     * @return string
     */
    public function getUpdatedByColumn()
    {
        return defined('static::UPDATED_BY') ? static::UPDATED_BY : 'updated_by';
    }

    /**
     * Get the name of the "created by" column.
     *
     * @return string
     */
    public function getCreatedByColumn()
    {
        return defined('static::CREATED_BY') ? static::CREATED_BY : 'created_by';
    }
}
