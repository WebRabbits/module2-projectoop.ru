<?php
require_once(__DIR__ . "/init.php");

$user = new User();

$validate = new Validate();

$validate->check($_POST, [
    "username" => [
        "required" => true,
        "min" => 2,
        "max" => 20,
        "unique" => "users"
    ]
]);

if (Input::exists()) {
    if (Token::check(Input::get("token"))) {

        if ($validate->passed()) {
            // вызываем метод из класса User для записи изменения в БД
            $user->update([
                "username" => Input::get("username")
            ]);
            Redirect::to("/");
        } else {
            foreach ($validate->errors() as $error) {
                echo "• <span style='color:red'>" . $error . "</span><br>";
            }
        }
    }
}

?>

<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?= $user->data()->username ?>">
    </div>

    <input type="hidden" name="token" value="<?= Token::generate() ?>">

    <div class="field">
        <button type="submit">Submit</button>
    </div>
</form>