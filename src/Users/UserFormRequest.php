<?php

namespace Atorscho\Uservel\Users;

use Atorscho\Http\Requests\Request;
use Atorscho\Uservel\Traits\ConfigFormRequest;

class UserFormRequest extends Request
{
    use ConfigFormRequest;

    /**
     * Configuration file namespace.
     *
     * @var string
     */
    protected $namespace = 'uservel.users.rules';

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
            'username' => $this->configRequired('username') . $this->config('username', ['min', 'max']),
            'email'    => 'email' . $this->configRequired('email') . $this->config('email', ['min', 'max']),
            'password' => 'sometimes|confirmed' . $this->configRequired('password') . $this->config('password', ['min', 'max']),
            'avatar' => 'image' . $this->configRequired('avatar')
        ];
    }
}
