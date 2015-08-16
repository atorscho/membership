<?php

namespace Atorscho\Uservel\Groups;

trait GroupsAttribute
{
    /**
     * Attach groups.
     *
     * @param string $groups Separate groups with a pipe "|".
     */
    public function setGroupsAttribute($groups)
    {
        // Get the ID
        $this->attributes['id'] = $this->orderBy('id', 'desc')->first()->id + 1;

        if ($groups == '*') {
            $this->addGroup(Group::lists('id')->all());

            return;
        }

        $groups = explode('|', $groups);

        $this->addGroup($groups);
    }
}
