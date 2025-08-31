<?php

class Validate
{
    private $passed = false; // Хранит результат успешной проверки данных
    private $errors = []; // Хранит массив полученных ошибок
    private $db = null; // Хранит значение подключения к БД

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

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function check($source, $items = [])
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value = $source[$item];

                if (empty($value) && $rule == "required") {
                    $this->addError("Поле \"$item\" обязательно для заполнения");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case "min":
                            if (strlen($value) < $rule_value) {
                                $this->addError("Значение поля \"$item\" должно быть минимум $rule_value символов");
                            }
                            break;

                        case "max":
                            if (strlen($value) > $rule_value) {
                                $this->addError("Значение поля \"$item\" не должно быть больше $rule_value символов");
                            }
                            break;

                        case "matches":
                            if ($value != $source[$rule_value]) {
                                $this->addError("Значения не совпадает с значением из поля \"$item\"");
                            }
                            break;

                        case "unique":
                            $checkValue = $this->db->get($rule_value, [$item, "=", $value]);
                            if ($checkValue->count()) {
                                $this->addError("Такое значение \"$item\" уже используется");
                            }
                            break;
                        case "email":
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                                $this->addError("Поле \"$item\" заполнено неверно");
                            }
                            break;
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
