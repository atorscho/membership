<?php

namespace Atorscho\Uservel\Traits;

trait ConfigFormRequest
{
    /**
     * Get a config value by its key using the proper namespace.
     * <br /><br />
     * E.g. "users.groups.name.min" => 4
     *
     * If input configuration is -1, the rule is disabled.
     *
     * @param string $key
     * @param string $rules
     *
     * @return string
     */
    protected function config($key, $rules)
    {
        $rules  = (array) $rules;
        $output = '';

        foreach ($rules as $rule) {
            $config = $this->getConfigValue($key, $rule);

            if ($config === null) {
                $output .= '';

                continue;
            }

            $output .= $this->formatRule($rule, $config);
        }

        return $output;
    }

    /**
     * Add a rule if a input rule configuration is true.
     * <br /><br />
     * E.g. 'description.required' => true
     *
     * @param string $key
     * @param string $rule
     *
     * @return string
     */
    protected function configBool($key, $rule)
    {
        return (!!$this->getConfigValue($key, $rule)) ? '|' . $rule : '';
    }

    /**
     * Add a required rule if configured.
     *
     * @param string $key
     *
     * @return string
     */
    protected function configRequired($key)
    {
        return $this->configBool($key, 'required');
    }

    /**
     * Get rule configuration value.
     *
     * @param string $key
     * @param string $rule
     *
     * @return mixed
     */
    protected function getConfigValue($key, $rule)
    {
        $namespace = trim($this->namespace, '\t\n\r\0\x0B.');

        return config("{$namespace}.{$key}.{$rule}");
    }

    /**
     * Convert string to a rule format.@deprecated
     * <br /><br />
     * E.g. 'rule:value'
     *
     * @param string $rule
     * @param string $config
     *
     * @return string
     */
    protected function formatRule($rule, $config)
    {
        return '|' . $rule . ':' . $config;
    }
}
