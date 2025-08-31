<?php
require_once(__DIR__ . "/init.php");

echo "==================== Validation and input ====================<br>";

if (Input::exists()) {
    if (Token::check(Input::get("token"))) {
        $validate = new Validate();

        $validate->check($_POST, [
            "username" => [
                "required" => true,
                "min" => 2,
                "max" => 15,
                "unique" => "users" // Хранится значение уникальности значения "Имя" из параметра "username" в БД таблице "users"
            ],
            "email" => [
                "required" => true,
                "email" => true,
                "unique" => "users"
            ],
            "password" => [
                "required" => true,
                "min" => 3
            ],
            "password_again" => [
                "required" => true,
                "matches" => "password" // Хранится значение названия поля из $_POST, с которым потребуется сравнить значение - поле "password"
            ]
        ]);

        if ($validate->passed()) {
            // echo "passed";
            // var_dump($_POST);
            // $hashPass = password_hash(Input::get("password"), PASSWORD_DEFAULT);
            // $password = password_verify(Input::get("password_again"), $hashPass);

            $user = new User();
            $user->create([
                "email" => Input::get("email"),
                "username" => Input::get("username"),
                "password" => password_hash(Input::get("password"), PASSWORD_DEFAULT)
            ]);

            Session::flash("success", "Register success");
            // Redirect::to("/reg_succ.php");
            // Redirect::to(404);
        } else {
            foreach ($validate->errors() as $error) {
                echo '• <span style="color:red;">' . $error . "</span><br>";
            }
        }
    }
}

?>

<form action="" method="post">
    <?= Session::flash("success");?>
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?= Input::get("username"); ?>">
    </div>
    <div class="field">
        <label for="email">Email</label>
        <input type="text" name="email" value="<?= Input::get("email"); ?>">
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
        <button type="submit">Register User</button>
    </div>
</form>