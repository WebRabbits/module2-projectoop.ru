<?php

class User
{
    private $db;
    private $data;
    private $session_name;
    private $isLoggedIn;
    private $cookieName;

    public function __construct($userId = null)
    {
        $this->db = Database::getInstance();
        $this->session_name = Config::get("session.session_user");
        $this->cookieName = Config::get("cookie.cookie_name");

        if (!$userId) {
            if (Session::exists($this->session_name)) {
                $userId = Session::get($this->session_name); // Получаем текущее id авторизованного пользователя

                if ($this->find($userId)) {
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

    public function update($fields = [], $id = null) {
        if(!$id && $this->isLoggedIn) { // Условие проверят, ЕСЛИ $id == null - то присваиваем в $id значение идентификатора текущего авторизованного пользователя. ЕСЛИ $id != null - то передаём для обновления записи в БД переданный при вызове метода идентификатор
            $id = $this->data()->id;
        }

        $this->db->update("users", $id, $fields);
    }

    public function login($email = null, $password = null, $remember = false)
    {
        if (!$email && !$password && $this->exists()) {
            Session::put($this->session_name, $this->data()->id); // Если нажато "Remember me", то при удалении сессии - пользователь будет всё равно авторизован, благодаря наличию активной куки
        } else {
            $user = $this->find($email);
            if ($user && isset($this->data()->password) && password_verify($password, $this->data()->password)) {
                Session::put($this->session_name, $this->data()->id);

                // Генерация и записи cookie для "Remember me"
                if ($remember) {
                    $hash = hash("sha256", uniqid());

                    $hashCheck = $this->db->get("user_session", ["user_id", "=", $this->data()->id]);
                    if (!$hashCheck->count()) {
                        $this->db->insert("user_session", [
                            "user_id" => $this->data()->id,
                            "hash" => $hash
                        ]);
                    } else {
                        $hash = $hashCheck->first()->hash;
                    }

                    Cookie::put($this->cookieName, $hash, Config::get("cookie.cookie_expired"));
                }

                return true;
            }
        }

        return false;
    }

    public function find($value = null)
    {
        if (is_numeric($value)) {
            $this->data = $this->db->get("users", ["id", "=", $value])->first();
        } else {
            $this->data = $this->db->get("users", ["email", "=", $value])->first();
        }

        if ($this->data) {
            return true;
        }

        return false;
    }

    public function data()
    {
        return $this->data;
    }

    public function exists() {
        if(isset($this->data)) {
            return true;
        }

        return false;
    }

    public function isLoggedIn()
    {
        return $this->isLoggedIn;
    }

    public function logout()
    {
        return Session::delete($this->session_name);
    }
}
