<?php 
    function getList($db, $listId, $frecencyInterval) {       
        //Get list from db
        try {
            //Determine list order and create query
            if ($_SESSION['orderBy'] =='frecency') {
                $query = "SELECT *, ROUND(numClicks/((CURRENT_DATE-firstClick)/ :frecencyInterval)) as calcfrec FROM ListItems WHERE listId = :listId ORDER BY calcfrec DESC";
                $statement = $db->prepare($query);
                $statement->execute(array(':frecencyInterval'=>$frecencyInterval, ':listId'=>$listId));
            } else if ($_SESSION['orderBy'] == 'category') {
                $query = "SELECT *, ROUND((numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval) as calcfrec FROM ListItems JOIN Categories on ListItems.category = Categories.category WHERE listId = :listId ORDER BY Categories.viewOrder ASC, calcfrec DESC";
                $statement = $db->prepare($query);
                $statement->execute(array(':frecencyInterval'=>$frecencyInterval, ':listId'=>$listId));
            } else {            
                $query = "SELECT *, '0' as calcfrec FROM ListItems WHERE listId = :listId ORDER BY title";           
                $statement = $db->prepare($query);
                $statement->execute(array(':listId'=>$listId));
            }           
            $listItems=$statement->fetchAll(PDO::FETCH_ASSOC);
            return $listItems;
        } catch (Exception $e) {
            echo $e;
        }
    }

    function getCategories($db) {
        $query = "SELECT * FROM Categories ORDER BY viewOrder";
        $statement = $db->prepare($query);
        $statement->execute(array());
        $categories=$statement->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }

    function getListItemById($db, $listItemId, $frecencyInterval) {
        //$query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems WHERE listItemId=:listItemId";
        $query = "SELECT * FROM ListItems WHERE listItemId=:listItemId";
        $statement = $db->prepare($query);
        $statement->execute(array(':listItemId'=>$listItemId));
        if (!$listItem=$statement->fetch(PDO::FETCH_ASSOC)) {
            //NOT YET IMPLEMENTED error
            echo "List Item not found.";
            return;
        }
        return $listItem;
    }
    function getListInfo($db) {
        //$query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems WHERE listItemId=:listItemId";
        $query = "SELECT * FROM Lists WHERE listId=:listId";
        $statement = $db->prepare($query);
        $statement->execute(array(':listId'=>$_SESSION['listId']));
        if (!$listInfo=$statement->fetch(PDO::FETCH_ASSOC)) {
            //NOT YET IMPLEMENTED error
            $result = "List not found.";
            return;
        }
        return $listInfo;
    }
    function getAllUserLists($db) {
        //$query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems WHERE listItemId=:listItemId";
        $query = "SELECT * FROM Lists WHERE listUserId=:listUserId";
        $statement = $db->prepare($query);
        $statement->execute(array(':listUserId'=>$_SESSION['userInfo']['userId']));
        if (!$allUserLists=$statement->fetchAll(PDO::FETCH_ASSOC)) {
            //NOT YET IMPLEMENTED error
            $result = "User lists not found.";
            return;
        }
        return $allUserLists;
    }

    //currently not in use, replaced with setting session variables on login instead
    function getUserInfo($db) {
        //$query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems WHERE listItemId=:listItemId";
        $query = "SELECT * FROM users WHERE userId=:userId";
        $statement = $db->prepare($query);
        $statement->execute(array(':userId'=>$_SESSION['userInfo']['userId']));
        if (!$userInfo=$statement->fetch(PDO::FETCH_ASSOC)) {
            //NOT YET IMPLEMENTED error
            $result = "User info not found.";
            return;           
        }
        return $userInfo;
    }
?>