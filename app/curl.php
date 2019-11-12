<?php

    require_once("../common/functions.php");

    $curl = curl_init();

    $request = '{
        "name": "generateToken",
        "params": {
            "email": "leogonzacr@gmail.com",
            "pass": "pass123"
        }
    }';

    curl_setopt($curl, CURLOPT_URL, 'http://leogonza.asuscomm.com:81/api/');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['content-type: application/json'] );
    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $err = curl_error($curl);
    if ($err){
        echo 'Curl Error:' . $err;
        exit();
    }
    print_r($result);

?>