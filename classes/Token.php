<?php

class Token
{
    // generate() - Генерирует случайную строку данных - это ТОКЕН
    public static function generate()
    {
        return Session::put(Config::get("session.token_name"), md5(uniqid()));
    }

    // check() - проверяет соответствие значение строки токена пришедшего при отправки формы с значением строки токена записанного в сессии 
    public static function check($token)
    {
        $tokenName = Config::get("session.token_name"); // Хранится значение "token"

        if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }

        Session::put($tokenName, ""); // При успехе, удаляем ключ и значение токена из S_SESSION - возникает ошибка что не определён ключ "token". И просто устанавливаем пустое значение по ключу "token"
        return false;
    }
}
