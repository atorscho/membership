<?php

namespace Atorscho\Uservel\Permissions;

use Atorscho\Uservel\Traits\ConfigFormRequest;

class PermissionFormRequest
{
    use ConfigFormRequest;

    /**
     * Configuration file namespace.
     *
     * @var string
     */
    protected $namespace = 'users.permissions.rules';

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
            'name'   => $this->configRequired('name') . $this->config('name', ['min', 'max']),
            'handle' => $this->configRequired('handle') . $this->config('handle', ['min', 'max'])
        ];
    }
}
