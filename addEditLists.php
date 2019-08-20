<?php include_once 'php/config/session.php'; ?>
<?php include_once 'php/config/config.php'; ?>
<?php include_once 'php/classes/Database.php'; ?>
<?php include_once 'php/reusables/queries.php'; ?>
<?php include_once 'php/reusables/helpers.php'; ?>
<?php //get user lists
    //redirect if user not logged in
    if (!isset($_SESSION['userInfo']['userId'])) {
        header("Location: login.php");
        exit();
    }
    //get user lists
    try {
        $query  = "SELECT * FROM Lists WHERE listUserId=:listUserId ORDER BY listName";
        $statement = $db->prepare($query);
        $statement->execute(array(':listUserId'=>$_SESSION['userInfo']['userId']));
        $lists = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOexception $ex) {
        $result = "An error occurred. Logout and login and try again.";
    }
    //if user has no lists 
    if (!$lists) {
        $result = "No lists found. Please go to profile from main menu and add a list.";
    }
?>
<?php //add list
    //if cancel button pressed
    if (isset($_POST['addListCancel'])) {
        header('Location: profile.php');
        exit();
    }
    //if submit button, prepare variables and sanitize input
    if (isset($_POST['addListSubmit'])) {
        $success=false;
        if (isset($_POST['addListName'])) {
            $addListName = testInput($_POST["addListName"]);
        }
        if (isset($_POST['isDefault'])) {
            $isDefault = testInput($_POST["isDefault"]);
            if ($isDefault == 'on') {
                $isDefault = 1;
            } else {
                $isDefault = 0;
            }
        } else {
            $isDefault = 0;
        }
        if (isset($_SESSION['userInfo']['userId'])) {
            $listUserId = $_SESSION['userInfo']['userId'];
        } else {
            //not yet implemented userId not found error message
            $result="User not found, please logout and login again.";
        }

        //if isDefault is true, turn off default on all other lists
        if ($isDefault) {
            try {
                //Create and execute query
                $query = "UPDATE Lists SET isDefault=0 WHERE listUserId=:listUserId";
                $statement = $db->prepare($query);
                $statement->execute(array(':listUserId' => $listUserId));
            } catch (Exception $e) {
                //NOT YET IMPLEMENTED
                $result = "Failed to turn default off on all but new list.";
            } 
        }
        //add list to backend
        try {
            $query = "INSERT INTO Lists (listName, listUserId, isDefault)
                                VALUES (:listName, :listUserId, :isDefault);";
            $statement = $db->prepare($query);
            $statement->execute(array(
                                ':listName'=>$addListName,
                                ':listUserId' => $listUserId, 
                                ':isDefault' => $isDefault
            ));
            $_SESSION['listId'] = $db->lastInsertId();
            $success=true;
        } catch (Exception $e) {
            //NOT YET IMPLEMENTED
            $result = "Something went wrong. List not added.";
        }
        //redirect on success
        if ($success) {
            header('Location: index.php');
            exit;
        }
    }
?>
<?php //Edit list   
    if(isset($_POST['saveButton'])){
        //Assign Vars
        $listUserId = $_SESSION['userInfo']['userId'];
        $listName = $_POST['listName'];
        $listId = $_POST['listId'];
        $isDefault='';
        $isDefaultOld='';
        if(isset($_POST['isDefault']) && $_POST['isDefault']=='on'){
            $isDefault = TRUE;
        }     
        if(!isset($_POST['isDefault'])) {
            $isDefault = 0;
        }
        if(isset($_POST['isDefaultOld']) && $_POST['isDefaultOld']=='on'){
            $isDefaultOld = TRUE;
        }         
        if(!isset($_POST['isDefaultOld'])) {
            $isDefaultOld = 0;
        }
        
        //in case user changing to a new default account
        if ($isDefaultOld == 0 && $isDefault == 1) {
            removeDefaultList($db, $_SESSION['userInfo']['userId']);
        }

        //Update List Data
        try {
            $query = "UPDATE Lists SET listName = :listName, isDefault = :isDefault WHERE listId=:listId";      
            $statement = $db->prepare($query);
            $statement->execute(array(':listName'=>$listName,
                                        ':isDefault'=>$isDefault,
                                        ':listId'=>$listId));
        } catch(PDOexception $ex) {
            $result = "Error updating list. Please login and logout and try again.";
            echo $result;
            exit();
        }             
        header("Location: addEditLists.php", true, 301);
        exit();
    }
?>
<?php //Delete list
    if((!isset($_POST['edit'])) && isset($_POST['delete'])){
        $listId = $_POST['listId'];
        try {
            $query = "DELETE FROM Lists WHERE listId = :listId";
            $statement = $db->prepare($query);
            $statement->execute(array(":listId"=>$listId));
            header("Location: addEditLists.php", true, 301);
            exit();
        } catch (PDOexception $ex) {
            $result = "Error occurred. List not deleted.";
        }        
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <div class="outer">
        <?php include 'php/reusables/mainnav.php'; ?>
        <div class="profile__line1">
            <h1>Lists <span>Page</span></h1>
        </div>        
        <!--If user message, show user message -->
        <?php include 'php/reusables/messageToUser.php'; ?>  
        <?php if($lists) : ?>           
            <div class="addEditLists__editArea">
                <!-- add list -->
                <div class="addEditLists__addArea">
                    <div class="addEditLists__addArea--title">
                       <h3>Add <span> List</span> </h3>
                    </div>
                    <?php include 'php/reusables/addList.php'; ?>
                </div>                            
                <br><hr><br>
                <!-- edit lists -->
                <div class="addEditLists__editArea--title">
                        <h3>Edit <span> Lists </span> </h3>
                </div>
                <?php include 'php/reusables/editLists.php'; ?>
            </div>
        <?php endif; ?>
    </div>  
</body>
</html>