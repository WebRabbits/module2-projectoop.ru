<?php
session_start();

require_once(__DIR__ . "/classes/Config.php");
require_once(__DIR__ . "/classes/Database.php");
require_once(__DIR__ . "/classes/Input.php");
require_once(__DIR__ . "/classes/Validate.php");
require_once(__DIR__ . "/classes/Token.php");
require_once(__DIR__ . "/classes/Session.php");
require_once(__DIR__ . '/classes/User.php');
require_once(__DIR__ . "/classes/Redirect.php");
require_once(__DIR__ . "/classes/Cookie.php");

$GLOBALS["config"] = [
    "mysql" => [
        "host" => "mysql-8.2",
        "username" => "root",
        "password" => "root",
        "database" => "module2_projectoop",
        "something" => [
            "no" => [
                "foo" => [
                    "bar" => "baz"
                ]
            ]
        ],
    ],
    "session" => [
        "token_name" => "token",
        "session_user" => "user"
    ],
    "cookie" => [
        "cookie_name" => "hash_cookie",
        "cookie_expired" => 604800
    ]
];

if (Cookie::exists(Config::get("cookie.cookie_name")) && !Session::exists(Config::get("session.session_user"))) {
    $hash = Cookie::get(Config::get("cookie.cookie_name"));
    $hashCheck = Database::getInstance()->get("user_session", ["hash", "=", $hash]);

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}
