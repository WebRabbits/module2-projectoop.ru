<?php
session_start();

require_once(__DIR__ . "/init.php");
// require_once(__DIR__ . "/classes/Config.php");
// require_once(__DIR__ . "/classes/Database.php");
// require_once(__DIR__ . "/classes/Input.php");
// require_once(__DIR__ . "/classes/Validate.php");
// require_once(__DIR__ . "/classes/Token.php");
// require_once(__DIR__ . "/classes/Session.php");
// require_once(__DIR__ . '/classes/User.php');
// require_once(__DIR__ . "/classes/Redirect.php");

// $GLOBALS["config"] = [
//     "mysql" => [
//         "host" => "mysql-8.2",
//         "username" => "root",
//         "password" => "root",
//         "database" => "module2_projectoop",
//         "something" => [
//             "no" => [
//                 "foo" => [
//                     "bar" => "baz"
//                 ]
//             ]
//         ],
//     ],
//     "session" => [
//         "token_name" => "token"
//     ]
// ];




// $users = Database::getInstance()->query("SELECT * FROM users");

// if ($users->error()) {
//     echo "Вы получили ошибку!";
// } else {
//     foreach ($users->results() as $user) {
//         echo $user->username . " " . $user->password . "<br>";
//     }
// }

// echo "==================== <br>";

// if ($users->count()) {
//     echo $users->count() . "<br>";
// }

// echo "==================== <br>";

// $onlyUser = Database::getInstance()->query("SELECT * FROM users WHERE username IN (?, ?)", ["John Doe", "Jane Koe"]);
// if ($onlyUser->error()) {
//     echo "Ошибка";
// } else {
//     foreach ($onlyUser->results() as $only) {
//         echo $only->username . "<br>";
//     }
// }

// echo "====================<br>";

// $getUser = Database::getInstance()->get("users", ["username", "=", "TestName1"]);
// // var_dump($getUser);
// // die();
// if ($getUser->error()) {
//     echo "Ошибка";
// } else {
//     foreach ($getUser->results() as $man) {
//         echo "Получили одного $man->username при помощи метода getUser()" . "<br>";
//     }
// }

// echo "==================== <br>";

// Database::getInstance()->delete("users", ["id", ">", 1300000]);

// echo "==================== <br>";

// // Database::getInstance()->insert(
// //     "users",
// //     [
// //         "username" => "TestName1",
// //         "password" => "TestPassword2",
// //         ]
// //     );

// // echo "==================== <br>";


// Database::getInstance()->update("users", 35, [
//     "username" => "TEST2211223333",
//     "password" => "TEST_PASSWORD",
// ]);

// echo "==================== <br>";

// $getUser = Database::getInstance()->get("users", ["username", "=", "TEST"]);
// echo $getUser->first()->username;

// echo "==================== <br>";
// echo "==================== <br>";
// echo "==================== Config ====================<br>";


// echo "==================== Validation and input ====================<br>";

// if (Input::exists()) {
//     if (Token::check(Input::get("token"))) {
//         $validate = new Validate();

//         $validation = $validate->check($_POST, [
//             "username" => [
//                 "required" => true,
//                 "min" => 2,
//                 "max" => 15,
//                 "unique" => "users" // Хранится значение уникальности значения "Имя" из параметра "username" в БД таблице "users"
//             ],
//             "password" => [
//                 "required" => true,
//                 "min" => 3
//             ],
//             "password_again" => [
//                 "required" => true,
//                 "matches" => "password" // Хранится значение названия поля из $_POST, с которым потребуется сравнить значение - поле "password"
//             ]
//         ]);

//         if ($validate->passed()) {
//             // echo "passed";
//             // var_dump($_POST);
//             // $hashPass = password_hash(Input::get("password"), PASSWORD_DEFAULT);
//             // $password = password_verify(Input::get("password_again"), $hashPass);

//             $user = new User();
//             $user->create([
//                 "username" => Input::get("username"),
//                 "password" => password_hash(Input::get("password"), PASSWORD_DEFAULT)
//             ]);

//             Redirect::to("/reg_succ.php");
//             Session::flash("success", "Register success");
//             // Redirect::to(404);
//         } else {
//             foreach ($validate->errors() as $error) {
//                 echo '• <span style="color:red;">' . $error . "</span><br>";
//             }
//         }
//     }
// }

// echo "Авторизация. ID-юзера из сессии - " . Session::get(Config::get("session.session_user"));

$user = new User();
// $anotherUser = new User(8); // Получить данные по юзеру с передачей конкретного ID-пользователя
// echo $user->data()->username . "<br>";
// echo $anotherUser->data()->username;

echo Session::flash("success") . "<br>";

if ($user->isLoggedIn()) {
    if($user->hasPermissions("admin")) {
        echo "Роль: ADMIN <br><br>";
    }
    echo "Hello <a href='#'>" . $user->data()->username . "</a><br>";
    echo "<a href='update.php'>Update profile</a><br>";
    echo "<a href='changepassword.php'>Change password</a><br>";
    echo "<a href='logout.php'>Logout</a>";

} else {
    echo "<a href='login.php'>Login</a> OR <a href='register.php'>Register</a>";
}

?>

<!-- <form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?= Input::get("username"); ?>">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="text" name="password">
    </div>
    <div class="field">
        <label for="password_again">Password Again</label>
        <input type="text" name="password_again">
    </div>

    <input type="hidden" name="token" value="<?= Token::generate(); ?>">

    <div class="field">
        <button type="submit">Submit</button>
    </div>
</form> -->