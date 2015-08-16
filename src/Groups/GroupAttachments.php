<?php

namespace Atorscho\Uservel\Groups;

use Exception;

trait GroupAttachments
{
    /**
     * Add user or permission to the group.
     *
     * @param int|string|Group|null $groups May be group ID, handle or model object.
     */
    public function addGroup($groups = null)
    {
        // If not specified, use the default one
        if (!$groups) {
            $groups = config('uservel.groups.default');
        }

        if ($groups instanceof Group) {
            $groups = [$groups];
        } else {
            $groups = (array) $groups;
        }

        foreach ($groups as $group) {
            if ($group instanceof Group) {
                $group = $group->id;
            } elseif (!is_numeric($group)) {
                $name = $group;
                $group = Group::whereHandle($group)->first();

                if (!$group) {
                    throw new Exception("Group [{$name}] does not exist.");
                }

                $group = $group->id;
            }

            // Attach the group
            $this->groups()->attach($group);
        }
    }

    /**
     * Remove user or permission from the group.
     *
     * @param int|string|Group $groups May be group ID, handle or model object.
     */
    public function removeGroup($groups)
    {
        if ($groups instanceof Group) {
            $groups = [$groups];
        } else {
            $groups = (array) $groups;
        }

        foreach ($groups as $group) {
            if ($group instanceof Group) {
                $group = $group->id;
            } elseif (!is_numeric($group)) {
                $group = Group::whereHandle($group)->first()->id;
            }

            // Detach the group
            $this->groups()->detach($group);
        }
    }

    /**
     * Remove all groups from a user or permission.
     */
    public function removeAllGroups()
    {
        $this->groups()->sync([]);
    }
}
