<?php 
    function getList($db, $listId, $frecencyInterval) {       
        //Get list from db
        try {
            //Determine list order and create query
            if ($_SESSION['orderBy'] =='frecency') {
                $query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems WHERE listId = :listId ORDER BY calcfrec DESC";
                $statement = $db->prepare($query);
                $statement->execute(array(':frecencyInterval'=>$frecencyInterval, ':listId'=>$listId));
            } else if ($_SESSION['orderBy'] == 'category') {
                $query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems JOIN Categories on ListItems.category = Categories.category WHERE listId = :listId ORDER BY Categories.viewOrder ASC, calcfrec DESC";
                $statement = $db->prepare($query);
                $statement->execute(array(':frecencyInterval'=>$frecencyInterval, ':listId'=>$listId));
            } else {            
                $query = "SELECT * FROM ListItems WHERE listId = :listId ORDER BY title";           
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

    function getListItemById($db, $id, $frecencyInterval) {
        //$query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems WHERE id=:id";
        $query = "SELECT * FROM ListItems WHERE id=:id";
        $statement = $db->prepare($query);
        $statement->execute(array(':id'=>$id));
        if (!$listItem=$statement->fetch(PDO::FETCH_ASSOC)) {
            echo "List Item not found.";
        }
        return $listItem;
    }
?>