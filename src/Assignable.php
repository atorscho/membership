<?php

namespace Atorscho\Membership;

trait Assignable
{
    /**
     * Assign the model to a group.
     *
     * @param int|string|Group $group
     */
    public function assignTo($group): void
    {
        $group = $this->resolveGroup($group);

        $this->groups()->attach($group);
    }

    /**
     * Unassign the model from a group.
     *
     * @param int|string|Group $group
     */
    public function unassignFrom($group): void
    {
        $group = $this->resolveGroup($group);

        $this->groups()->detach($group);
    }

    /**
     * Check whether the model is assigned to a group.
     *
     * @param int|string|Group $group
     *
     * @return bool
     */
    public function isAssignedTo($group): bool
    {
        $group = $this->resolveGroup($group);

        return $this->groups()->where('group_id', is_int($group) ? $group : $group->id)->exists();
    }

    /**
     * Resolve the group parameter.
     *
     * @param int|string|Group $group
     *
     * @return Group|int
     */
    protected function resolveGroup($group)
    {
        if (is_string($group)) {
            $group = Group::whereHandle($group)->firstOrFail();
        }

        return $group;
    }
}
