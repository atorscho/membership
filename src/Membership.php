<?php

namespace Atorscho\Membership;

class Membership
{
    /**
     * Determine if the current user is authenticated.
     */
    public function isLoggedIn(): bool
    {
        return auth()->check();
    }

    /**
     * Determine if the current user is a guest.
     */
    public function isGuest(): bool
    {
        return !$this->isLoggedIn();
    }

    /**
     * Retrieve currently authenticated user or its attribute value.
     *
     * @param string|null $attribute Optional.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|string|null
     */
    public function user(?string $attribute = null)
    {
        if ($this->isGuest()) {
            return null;
        }

        $user = auth()->user();

        if ($attribute) {
            return $user->{$attribute};
        }

        return $user;
    }
}
