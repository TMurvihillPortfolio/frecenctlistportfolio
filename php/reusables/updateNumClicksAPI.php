<?php
//include settings from config file.
//$config = require 'config.php';

//start config.php
    //for database connection
    $driver = 'mysql';
    $host = 'localhost:3306';
    $dbname = 'tmurvvvv_frecency';
    $username = 'tmurvvvv_me';
    $password = '!Tm427712*';
    $dsn = "{$driver}:host={$host}; dbname={$dbname}";

    //for frecency calculations
    //!!!!! WARNING if this number changed all list item numCLicks need to be updated in database
    $frecencyPeriodInDays = 100; //NOT YET IMPLEMENTED if this number changed all list items need to be updated in database
//end config.php

//set variables from config file array
// $driver = $config['database']['driver'];
// $host = $config['database']['host'];
// $dbname = $config['database']['dbname'];
// $db_username = $config['database']['username'];
// $db_password = $config['database']['password'];
// $dsn = "{$driver}:host={$host}; dbname={$dbname}";

//connect to the database
try {
    $db = new PDO($dsn,$username,$password);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected to frecency database";
} catch (PDOException $ex){
    echo "Connection failed ".$ex->getMessage();
}

$updateClicksId = $_REQUEST['id'];
$isChecked = $_REQUEST['ischecked'];

if ($isChecked==0) {
    $query = "UPDATE ListItems SET numClicks = (numClicks + 1), isChecked = 1 WHERE id = :updateClicksId";
    $statement = $db->prepare($query);
    $statement->execute(array(":updateClicksId"=>$updateClicksId));
} else {
    $query = "UPDATE ListItems SET isChecked = 0 WHERE id = :updateClicksId";
    $statement = $db->prepare($query);
    $statement->execute(array(":updateClicksId"=>$updateClicksId));
}

return;