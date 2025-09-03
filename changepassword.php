<?php 
require_once(__DIR__ . "/init.php");

$user = new User();

$validate = new Validate();
$validate->check($_POST, [
    "current_password" => [
        "required" => true,
    ],
    "change_password" => [
        "required" => true,
        "min" => 3
    ],
    "change_password_again" => [
        "required" => true,
        "min" => 3,
        "matches" => "change_password"
    ]
]);

// var_dump($user->data());

if(Input::exists()) {
    if(Token::check(Input::get("token"))) {
        if($validate->passed()) {
            // производим редирект на страницу профиля
            if(!password_verify(Input::get("current_password"), $user->data()->password)) {
                echo "Введён неправильный текущий пароль";
            } else {
                $user->update([
                    "password" => password_hash(Input::get("change_password"), PASSWORD_DEFAULT)
                ]);
                Session::flash("success", "Пароль был изменён успешно!");
                Redirect::to("/");
            }

        } else {
            foreach($validate->errors() as $error) {
                echo "- <span style='color:red'>" . $error . "</span><br>";
            }
        }
    }
}


?>

<form action="" method="post">
    <div class="field">
        <label for="current_password">Current password</label>
        <input type="text" name="current_password">
    </div>
    <div class="field">
        <label for="change_password">Change password</label>
        <input type="text" name="change_password">
    </div>
    <div class="field">
        <label for="change_password_again">Change password again</label>
        <input type="text" name="change_password_again">
    </div>
    <div class="field">
        <button type="submit">Submit</button>

    <input type="hidden" name="token" value="<?= Token::generate()?>">
    </div>
</form>