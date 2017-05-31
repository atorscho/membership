<?php

namespace Atorscho\Membership;

/**
 * Trait Handlable
 * Ensure the "handle" attribute in Group and Permission records
 * is always set and is in correct format.
 *
 * @package Atorscho\Membership
 * @author  Alex Torscho <contact@alextorscho.com>
 * @version 2.0.0
 */
trait Handlable
{
    /**
     * Set the handle if missing.
     */
    protected static function bootHandlable()
    {
        static::creating(function ($model) {
            if (!$model->handle) {
                $model->handle = '';
            }
        });

        static::updating(function ($model) {
            if (!$model->handle) {
                $model->handle = '';
            }
        });
    }

    /**
     * Set model's handle if none was provided.
     */
    public function setHandleAttribute(?string $handle): void
    {
        $this->attributes['handle'] = str_slug($handle ?: $this->name);
    }
}
