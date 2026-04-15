<?php

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $validated = [];

    private function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    private function validate(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $value = $this->data[$field] ?? null;
            $rules = explode('|', $ruleString);

            foreach ($rules as $rule) {
                $ruleName = $rule;
                $params = [];

                if (str_contains($rule, ':')) {
                    [$ruleName, $paramString] = explode(':', $rule, 2);
                    $params = explode(',', $paramString);
                }

                $this->applyRule($field, $value, $ruleName, $params);
            }

            if (!isset($this->errors[$field])) {
                $this->validated[$field] = is_string($value) ? trim($value) : $value;
            }
        }
    }

    private function applyRule(string $field, mixed $value, string $ruleName, array $params = []): void
    {
        if (isset($this->errors[$field])) {
            return;
        }

        switch ($ruleName) {
            case 'required':
                if (trim((string) $value) === '') {
                    $this->errors[$field] = "Le champ {$field} est obligatoire.";
                }
                break;

            case 'min':
                $min = (int) ($params[0] ?? 0);
                if (trim((string) $value) !== '' && mb_strlen(trim((string) $value)) < $min) {
                    $this->errors[$field] = "Le champ {$field} doit contenir au moins {$min} caractères.";
                }
                break;

            case 'in':
                $allowed = $params;
                if (trim((string) $value) !== '' && !in_array((string) $value, $allowed, true)) {
                    $this->errors[$field] = "La valeur du champ {$field} est invalide.";
                }
                break;

            case 'integer':
                if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $this->errors[$field] = "Le champ {$field} doit être un entier.";
                }
                break;

            case 'between':
                $min = (int) ($params[0] ?? 0);
                $max = (int) ($params[1] ?? 0);
                $number = (int) $value;

                if ($number < $min || $number > $max) {
                    $this->errors[$field] = "Le champ {$field} doit être compris entre {$min} et {$max}.";
                }
                break;
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return empty($this->errors) ? null : reset($this->errors);
    }

    public function validated(): array
    {
        return $this->validated;
    }
}