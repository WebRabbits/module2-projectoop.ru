<?php

class User
{
    private $db;
    private $data;
    private $session_name;
    private $isLoggedIn;

    public function __construct($userId = null)
    {
        $this->db = Database::getInstance();
        $this->session_name = Config::get("session.session_user");
        
        if(!$userId) {
            if(Session::exists($this->session_name)) {
                $userId = Session::get($this->session_name); // Получаем текущее id авторизованного пользователя

                if($this->find($userId)) {
                    $this->isLoggedIn = true;
                } else {
                    // logout
                }
            }
        } else {
            $this->find($userId);
        }
    }

    public function create($fields = [])
    {
        $this->db->insert("users", $fields);
    }

    public function login($email = null, $password = null) {
        if($email) {
           $user = $this->find($email);
           if($user && isset($this->data()->password) && password_verify($password, $this->data()->password)) {
            Session::put($this->session_name, $this->data()->id);
            return true;
           }
        }

        return false;
    }

    public function find($value = null) {
        if(is_numeric($value)) {
            $this->data = $this->db->get("users", ["id", "=", $value])->first();
        } else{
            $this->data = $this->db->get("users", ["email", "=", $value])->first();
        }
        
        if($this->data) {
            return true;
        }

        return false;
    }

    public function data(){
        return $this->data;
    }

    public function isLoggedIn() {
        return $this->isLoggedIn;
    }

    public function logout(){
        return Session::delete($this->session_name);
    }
}
