<?php

namespace Atorscho\Uservel\Traits;

trait HandleAttribute
{
    /**
     * Ensure handle is always set and is in dot notation.
     *
     * @param string $handle
     */
    public function setHandleAttribute($handle)
    {
        if ($handle) {
            $this->attributes['handle'] = str_slug($handle, '.');
        } else {
            $this->attributes['handle'] = str_slug($this->attributes['name'], '.');
        }
    }
}
