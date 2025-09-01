<?php
require_once(__DIR__ . "/init.php");

if (Input::exists()) {
    if (Token::check(Input::get("token"))) {
        $validate = new Validate();

        $validate->check($_POST, [
            "email" => [
                "required" => true,
                "email" => true,
            ],
            "password" => [
                "required" => true,
            ]
        ]);

        if ($validate->passed()) {
            $user = new User();
            $remember = Input::get("remember") === "on" ? true : false;
            $login = $user->login(Input::get("email"), Input::get("password"), $remember);

            if ($login) {
                Redirect::to("/");
            } else {
                echo "Login failed";
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo '. <span style="color:red">' . $error . "</span><br>";
            }
        }
    }
}

?>

<form action="" method="post">
    <div class="field">
        <label for="email">Email</label>
        <input type="text" name="email" value="<?= Input::get("email"); ?>">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="text" name="password">
    </div>
    <div class="field">
        <input type="checkbox" name="remember">
        <label for="remember">Remember me</label>
    </div>

    <input type="hidden" name="token" value="<?= Token::generate(); ?>">

    <div class="field">
        <button type="submit">Login</button>
    </div>
</form>