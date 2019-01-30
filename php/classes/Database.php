<?php
    //include settings from config file.
    $config = include_once 'php/config/config.php';

    //connect to the database
    try {
        $db = new PDO($dsn,$username,$password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected to frecency database";
    } catch (PDOException $ex){
        //NOT YET IMPLEMENTED -- error handling
        //echo "Connection failed ".$ex->getMessage();
    }
?>