<?php

    spl_autoload_register(function ($className){

        $folders = ['api','app', 'common', 'dal'];
        $file = strtolower($className) . ".php";
        $root = $_SERVER['DOCUMENT_ROOT'];
        $found = false;
        foreach ($folders as $folder){
            $path = $root . "/$folder/" . $file;
            if (file_exists ($path)){
                require_once ($path);
                $found =true;
                break;
            }
        }

        if (!$found)
            die("Autoload file $file not found");
    });

    function guidv4() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

?>