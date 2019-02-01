<?php
    //for database connection
    $driver = 'mysql';
    $host = 'localhost:3306';
    $dbname = 'tmurvvvv_frecency';
    $username = 'tmurvvvv_me';
    $password = '!Tm427712*';
    $dsn = "{$driver}:host={$host}; dbname={$dbname}";

    //for frecency calculations
    //!!!!! WARNING if this number changed all list item numCLicks need to be updated in database
    $frecencyInterval = 100; //NOT YET IMPLEMENTED if this number changed all list items need to be updated in database
?>