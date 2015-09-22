<?php

namespace Atorscho\Uservel\Groups;

use Atorscho\Uservel\Traits\ConfigFormRequest;

class GroupFormRequest
{
    use ConfigFormRequest;

    /**
     * Configuration file namespace.
     *
     * @var string
     */
    protected $namespace = 'membership.groups.rules';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => $this->configRequired('name') . $this->config('name', ['min', 'max']),
            'handle'      => $this->configRequired('handle') . $this->config('handle', ['min', 'max']),
            'description' => $this->configRequired('description') . $this->config('description', ['min', 'max']),
            'prefix'      => $this->configRequired('prefix') . $this->config('prefix', ['min', 'max']),
            'suffix'      => $this->configRequired('suffix') . $this->config('suffix', ['min', 'max']),
            'permissions' => 'required'
        ];
    }
}
