<?php 

class Cookie{
    public static function exists($name) {
        return (isset($_COOKIE[$name])) ? true : false;
    }
    
    public static function get($name){
        return $_COOKIE[$name];
    }

    public static function put($name, $value_hash, $expire) {
        if(setcookie($name, $value_hash,  time() + $expire, "/")) {
            return true;
        }

        return false;
    }

    public static function delete($name) {
        self::put($name, "", time() - 1);
    }
}

?>