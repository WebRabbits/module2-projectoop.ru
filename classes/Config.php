<?php 

class Config{
    public static function get($path = null) {
        if($path) {
            $config = $GLOBALS["config"];

            $path = explode(".", $path);

            foreach($path as $val) {
                if(isset($config[$val])) {
                    $config = $config[$val];
                }
            }

            return $config;   
        }

        return false;
    }
}


?>