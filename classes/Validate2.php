<?php

class Validate
{
    private $passed = false;
    private $errors = [];
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function passed()
    {
        return $this->passed;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function addError($valError)
    {
        $this->errors[] = $valError;
    }

    public function check($source, $items = [])
    {
        // var_dump($items);

        foreach ($items as $item => $rules) {
            // var_dump($item);
            // var_dump($rules);
            foreach ($rules as $rule => $rule_value) {
                // var_dump($rule);
                // var_dump($rule_value);
                $value = $source[$item];
                // var_dump($value);

                // var_dump($source);
                // die;


                if (empty($value) && $rule == "required") {
                    $this->addError("Значения поля $item не может быть пустым");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case "min":
                            if (strlen($value) < $rule_value) {
                                $this->addError("Длина значения поля $item не может быть меньше $rule_value символов");
                            }
                            break;
                        case "max":
                            if (strlen($value) > $rule_value) {
                                $this->addError("Длина значения поля $item не может быть больше $rule_value символов");
                            }
                            break;
                        case "unique":
                            if ($this->db->get("users", [$item, "=", $value])->count() > 0) {
                                $this->addError("Такое имя из поля $item уже занято");
                            }
                            break;
                        case "matches":
                            if ($value != $source[$rule_value]) {
                                $this->addError("Значение в поле $item должно совпадать с паролем");
                            }
                    }
                }
            }
        }

        if (empty($this->errors)) {
            return $this->passed = true;
        } else {
            return $this;
        }
    }
}
