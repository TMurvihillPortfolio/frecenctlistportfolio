<?php
    
    $rootDirectory = 'https://www.take2tech.ca/TTT/MyProjects/FrecencyListPHP/';
    //for database connection
    $driver = 'mysql';
    $host = 'localhost:3306';
    $dbname = 'tmurvvvv_frecency';
    $username = 'tmurvvvv_me';
    $password = '!Tm427712*';
    $dsn = "{$driver}:host={$host}; dbname={$dbname}";

    //for frecency calculations
    //!!!!! WARNING if this number changed all list item numCLicks need to be updated in database
    $frecencyInterval = 5; //NOT YET IMPLEMENTED if this number changed all list items need to be updated in database
?>

<?php

    //connect to the database
    try {
        $db = new PDO($dsn,$username,$password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected to frecency database";
    } catch (PDOException $ex){
        //NOT YET IMPLEMENTED -- error handling
        //echo "Connection failed ".$ex->getMessage();
    }

    $statement = $db->prepare( "DELETE FROM ListItems WHERE listId =:listId" );
                $statement->execute(array(':listId'=>9));
                echo $statement->rowCount();
?>