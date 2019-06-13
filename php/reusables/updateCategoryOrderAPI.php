<?php
    include("../config/config.php");
    include("../config/session.php");

    //connect to the database
    try {
        $db = new PDO($dsn,$username,$password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected to frecency database";
    } catch (PDOException $ex){
        echo "Connection failed ".$ex->getMessage();
    }

    //Get posted variables
    $catList = $_REQUEST['catlist'];

    //update db category order
    try {
        $query = "UPDATE customCategories SET custCatList=:catList WHERE custCatUserId = :id";
        $statement = $db->prepare($query);
        $statement->execute(array(":catList"=>$catList, ":id"=>$_SESSION['userInfo']['userId']));
    } catch(PDOException $e) {
        $result = "Problem update category order: ".$e;
    }
?>