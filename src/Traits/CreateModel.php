<?php

namespace Atorscho\Uservel\Traits;

trait CreateModel
{
    /**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     *
     * @return static
     */
    public static function create(array $attributes = [])
    {
        // Get groups or permissions from the attributes array then remove it
        if (isset($attributes['groups'])) {
            $groups = explode('|', $attributes['groups']);
            unset($attributes['groups']);
        } elseif (isset($attributes['permissions'])) {
            $permissions = explode('|', $attributes['permissions']);
            unset($attributes['permissions']);
        }

        $model = parent::create($attributes);

        if (isset($groups)) {
            $model->addGroup($groups);
        } elseif (isset($permissions)) {
            $model->addPermission($permissions);
        }

        return $model;
    }
}
