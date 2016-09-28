<?php

namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;
use Session;

class Validator
{
    protected $errors;

    public function validate($request, array $rules)
    {
        foreach ($rules as $field => $rule) {
            $split = explode('|', $field);
            try {
                $rule->setName(isset($split[1]) ?
                    $split[1] :
                    ucfirst($split[0]))->assert($request->getParam($split[0]));
            } catch (NestedValidationException $e) {
                $this->errors[$split[0]] = $e->getMessages();
            }
        }

        Session::set("validation_errors", $this->errors);

        return $this;
    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function fails()
    {
        return !$this->passes();
    }

    public function addToErrors($key, $value)
    {
        $errors = Session::get("validation_errors");
        $errors[$key] = $value;
        Session::set("validation_errors", $errors);
    }

    public function flush()
    {
        $this->errors = [];
        return $this;
    }
}
