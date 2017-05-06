<?php

namespace Atorscho\Membership;

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
        $this->attributes['handle'] = $handle ? str_slug($handle) : str_slug($this->name);
    }
}
