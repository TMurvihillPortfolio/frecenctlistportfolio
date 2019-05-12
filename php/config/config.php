<?php
    //enable error reporting
    // error_reporting(-1);
    // ini_set('display_errors', 'On');
    // set_error_handler("var_dump");
    
    $rootDirectory = 'https://take2tech.ca/TTT/MyProjects/FrecencyListPHP';

    //for database connection
    $driver = 'mysql';
    $host = 'localhost:3306';
    $dbname = 'tmurvvvv_frecency';
    $username = 'tmurvvvv_me';
    $password = '!Tm427712*';
    $dsn = "{$driver}:host={$host}; dbname={$dbname}";

    //for frecency calculations
    //time interval in seconds where 1 click per interval = 1 on the frecency scale, 604800 = 1 week
    //!!!!! WARNING if this number changed all list item numCLicks need to be updated in database
    $frecencyInterval = 604800; //in seconds //NOT YET IMPLEMENTED if this number changed all list items need to be updated in database
    $minimumIntervals = 10; //On add or edit item firstClick will be updated in database to at least 12 intervals back in time
?>