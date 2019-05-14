<?php include_once 'php/config/session.php'; ?>
<?php include_once 'php/config/config.php'; ?>
<?php include_once 'php/classes/Database.php'; ?>
<?php include_once 'php/reusables/queries.php'; ?>
<?php include_once 'php/reusables/helpers.php'; ?>
<?php
    if (!isset($_SESSION['userInfo']['userId'])) {
        header("Location: login.php");
        exit();
    }

    try {
        //Run  list  Query
        $query  = "SELECT * FROM Lists WHERE listUserId=:listUserId ORDER BY listName";
        $statement = $db->prepare($query);
        $statement->execute(array(':listUserId'=>$_SESSION['userInfo']['userId']));
        $lists = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOexception $ex) {
        $result = "An error occurred. Logout and login and try again.";
    }   
    if (!$lists) {
        $result = "No lists found. Please go to profile from main menu and add a list.";
    }
?>
<?php //add  list
    if (isset($_POST['addListCancel'])) {
        header('Location: profile.php');
        exit();
    }
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
        if (isset($_SESSION['userId'])) {
            $listUserId = $_SESSION['userId'];
        } else {
            //not yet implemented userId not found error message
            $result="User not found, please logout and login again.";
        }

        //if isDefault is true, turn off on all other lists
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

        if ($success) {
            header('Location: index.php');
            exit;
        }
    }
?>
<?php
    //Edit transaction
    // if (isset($_POST) && count($_POST)>0){
    //     echo count($_POST);
    // var_dump($_POST);
    //exit();
    // }
    
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
        <?php if (isset($result)) : ?>
            <div class="signatureBox">           
                <p style="color: #153a52;;"><?php echo isset($result) ? $result : ''; ?></p>
            </div>
        <?php endif; ?>  
        <?php if($lists) : ?>
            <div class="addEditLists__editArea">
                <div class="addEditLists__addArea">
                    <div class="addEditLists__addArea--title">
                       <h3>Add <span> List</span> </h3>
                    </div>
                    <form action="addEditLists.php" method="post">
                        <div class="flex">
                            <div class="addEditLists__addArea--name">
                                <label for="email">List Name: </label>
                                <input name="addListName" type="text" required>                                           
                            </div>
                            <div class="addEditLists__addArea--default">
                                <label for="default">Set as default? </label>
                                <input name="isDefault" type="checkbox">             
                            </div>
                        </div>
                        <div class="addEditLists__addArea--submit">                             
                            <button name="addListSubmit" type="submit" class="btn btn__secondary">Add List</button>
                            <button name="addListCancel" type="reset" class="btn btn__primaryVeryDark" id="addListCancel">Reset</button>                 
                        </div>
                    </form> 
                </div>                            
                <br>
                <hr>
                <br>
                <div class="addEditLists__editArea--title">
                        <h3>Edit <span> Lists </span> </h3>
                </div>
                <?php foreach($lists as $list) : ?>
                    <form method="post" name="edit" action="addEditLists.php">                  
                        <div class="addEditLists__list--lineItem">
                            <?php if($list['isDefault']==1) {$checked = 'checked';}else{$checked="";} ?>
                            <div class="addEditLists__list--lineItem-listName" id="js--listName">
                                <p><?php echo $list['listName']; ?>&nbsp;<span><?php echo $checked ? '(default)':''; ?></span></p>
                            </div>
                            <div class="addEditLists__list--lineItem-listName" hidden>
                                <input class="addEditLists__list--lineItem-listName" name="listName" id="js--listNameEdit" type="text" value="<?php echo $list['listName']; ?>"/>                            
                            </div>
                            
                            <div class="addEditLists__list--lineItem-isDefault">
                                <!-- <label for="isDefault">Default?</label> -->
                                <input class="addEditLists__list--lineItem-isDefault" id="js--isDefault" name="isDefault" type="checkbox" <?php echo $checked ?> hidden/>
                            </div>
                            <div class="addEdit___list--lineItem-isDefault" hidden>
                                <label for="isDefault">Default?</label>
                                <input class="addEditLists__list--lineItem-isDefault" id="js--isDefaultEdit" name="isDefault" type="checkbox" <?php echo $checked ?> />
                            </div>
                            <div class="addEditLists__list--lineItem-listId" hidden>
                                <input class="list__lineItem--listId" name="listId" type="text" value="<?php echo $list['listId']; ?>" hidden/>
                            </div>
                            <div class="addEditLists__list--lineItem-editDelete">
                                <button type='button' class="addEditLists__list--lineItem-editDeletePencil" name="editPencil" onClick="startEditLists(this)"><img src="./img/editItemIcon.png" alt="Pencil icon for edit list item"></button>                                                                                                
                                <button type='button' class="addEditLists__list--lineItem-editDeleteX" name='delete' onClick="deleteList(this);"><img src="./img/deleteRedX.png" name="deleteItem" alt="Big red X icon for delete list item"></button>
                                <button name="saveButton" type="button" class="btn btn__secondary" onClick="startEditLists(this)" hidden>Save</button>
                                <button name="cancelButton" type="button" class="btn btn__primaryVeryDark" onClick="startEditLists(this)" hidden>Cancel</button>
                            </div>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>  
</body>
</html>