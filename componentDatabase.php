<?php

class Database
{
    private static $instance = null;
    private $pdo, $query, $error = false, $results, $count;

    private function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . Config::get("mysql.host") . ";dbname=" . Config::get("mysql.database"), Config::get("mysql.username"), Config::get("mysql.password"));
        } catch (PDOException $err) {
            die($err->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function query($sql, $params = [])
    {

        $this->query = $this->pdo->prepare($sql);

        if (count($params)) {
            foreach ($params as $key => $value) {
                $this->query->bindValue(++$key, $value);
            }
        }

        if (!$this->query->execute()) {
            $this->error = true;
        } else {
            $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
            $this->count = $this->query->rowCount();
        }

        return $this;
    }

    public function error()
    {
        return $this->error;
    }

    public function results()
    {
        return $this->results;
    }

    public function count()
    {
        return $this->count;
    }

    public function get($table, $where = [])
    {
        return $this->action("SELECT *", $table, $where);
    }

    public function delete($table, $where = [])
    {
        $this->action("DELETE", $table, $where);
        return $this->count();
    }

    public function insert($table, $fields = [])
    {
        $values = "";
        foreach ($fields as $value) {
            $values .= "?,";
        }
        $values = rtrim($values, ',');


        $sql = "INSERT INTO $table " . " (`" . implode("`, `", array_keys($fields)) . "`)" . " VALUES ($values)";

        if (!$this->query($sql, array_values($fields))->error()) {
            return true;
        }

        return false;
    }

    public function update($table, $id, $fields = []) {

        $values = "";
        foreach($fields as $key => $field) {
            $values .= $key . " = ?, ";
        }
        $values = rtrim($values, ", ");

        $sql = "UPDATE $table SET $values WHERE id = ?";

        $fields["id"] = $id; // Добавление в массив значений доп.параметра $id для передачи в нумерованный placeholder
        
        if(!$this->query($sql, array_values($fields))->error) {
            return true;
        }

        return false;
    }


    public function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = ["=", ">", "<", ">=", "<=", "!=", "LIKE"];

            [$field, $operator, $value] = $where;

            if (in_array($operator, $operators, true)) {
                $sql = "$action FROM $table WHERE $field $operator ?";
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    public function first() {
        return $this->results()[0];
    }
}
