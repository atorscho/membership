<?php

if (!function_exists('is_logged_in')) {
    /**
     * Determine if the current user is authenticated.
     */
    function is_logged_in(): bool
    {
        return Membership::isLoggedIn();
	}
}

if (!function_exists('is_guest')) {
    /**
     * Determine if the current user is a guest.
     */
    function is_guest(): bool
    {
        return Membership::isGuest();
    }
}

if (!function_exists('user')) {
    /**
     * Retrieve currently authenticated user or its attribute value.
     *
     * @param string|null $attribute Optional.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|string|null
     */
    function user(?string $attribute = null)
	{
        return Membership::user($attribute);
	}
}
