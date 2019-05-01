<?php include_once 'php/config/session.php'; ?>
<?php include_once 'php/config/config.php'; ?>
<?php include_once 'php/classes/Database.php'; ?>
<?php include_once 'php/reusables/queries.php'; ?>
<?php include_once 'php/reusables/helpers.php'; ?>
<?php
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

<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <div class="outer">
        <?php include 'php/reusables/mainnav.php'; ?>
        <div class="profile__line1">
            <h2>Add/Edit Lists <span>Page</span></h2>
        </div>        
        <?php if (isset($result)) : ?>
            <div class="signatureBox">           
                <p style="color: #153a52;;"><?php echo isset($result) ? $result : ''; ?></p>
            </div>
        <?php endif; ?>  

        <div class="addEditLists__addArea signatureBox">
            <div class="addEditLists__addArea--title">
                    <h3>Add <span> List</span> </h3>
            </div>
            <form action="addEditLists.php" method="post">
                <div class="addEditLists__addArea--name">
                    <label for="email">List Name: </label>
                    <input name="addListName" type="text" required>                                           
                </div>
                <div class="addEditLists__addArea--default">
                    <label for="default">Set as default? </label>
                    <input name="isDefault" type="checkbox">             
                </div>
                <div class="addEditLists__addArea--submit">                             
                    <button name="addListSubmit" type="submit" class="btn btn__secondary profile__form--changeProfileButton">Add List</button>
                    <button name="addListCancel" type="button" class="btn btn__primaryVeryDark profile__form--changeProfileButton" onClick="startAddList(this)" id="js--addListCancel">Cancel</button>                 
                </div>
            </form>
        </div>
    </div>

    
</body>
</html>