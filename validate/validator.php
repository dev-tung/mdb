<?php

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data  = $data;
        $this->rules = $rules;
        $this->validate();
    }

    private function validate()
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);

            foreach ($rules as $rule) {
                $ruleName  = $rule;
                $ruleValue = null;

                // rule with parameter, e.g. min:3
                if (str_contains($rule, ':')) {
                    [$ruleName, $ruleValue] = explode(':', $rule);
                }

                $value = $this->data[$field] ?? null;

                switch ($ruleName) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $this->addError($field, 'This field is required.');
                        }
                        break;

                    case 'numeric':
                        if ($value !== null && !is_numeric($value)) {
                            $this->addError($field, 'The value must be a number.');
                        }
                        break;

                    case 'min':
                        if ($value !== null && strlen($value) < (int)$ruleValue) {
                            $this->addError($field, "Minimum length is $ruleValue characters.");
                        }
                        break;

                    case 'max':
                        if ($value !== null && strlen($value) > (int)$ruleValue) {
                            $this->addError($field, "Maximum length is $ruleValue characters.");
                        }
                        break;

                    case 'email':
                        if ($value !== null && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, 'Invalid email format.');
                        }
                        break;

                    // add more rules if needed
                }
            }
        }
    }

    private function addError(string $field, string $message)
    {
        $this->errors[$field][] = $message;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}

// Helper function similar to Laravel
function validate(array $data, array $rules): Validator
{
    return new Validator($data, $rules);
}
