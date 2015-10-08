<?php

namespace Atorscho\Membership\Groups;

trait ManageGroups
{
    /**
     * Add user to the specified group.
     *
     * @param Group|string $groups Group instance, string (separated by a "|"),
     *                             or an array of groups.
     */
    public function assignTo($groups)
    {
        // If the parameter is an instance of Group, attach it
        if ($groups instanceof Group) {
            return $this->groups()->attach($groups);
        }

        // If the parameter is a string, explode it to an array by '|'
        if (is_string($groups)) {
            $groups = explode('|', $groups);
        }

        // Convert all array items to an instance of Group
        foreach ($groups as $group) {
            if (is_string($group)) {
                $group = Group::whereHandle($group)->first();
            }

            // Attach the group
            $this->groups()->attach($group);
        }
    }

    /**
     * Remove the user from the specified group.
     *
     * @param Group|string $groups Group instance, string (separated by a "|"),
     *                             or an array of groups.
     */
    public function removeFrom($groups)
    {
        // If the parameter is an instance of Group, attach it
        if ($groups instanceof Group) {
            return $this->groups()->detach($groups);
        }

        // If the parameter is a string, explode it to an array by '|'
        if (is_string($groups)) {
            $groups = explode('|', $groups);
        }

        // Convert all array items to an instance of Group
        foreach ($groups as $group) {
            if (is_string($group)) {
                $group = Group::whereHandle($group)->first();
            }

            // Attach the group
            $this->groups()->detach($group);
        }
    }
}
