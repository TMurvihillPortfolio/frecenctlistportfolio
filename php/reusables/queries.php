<?php 
    function getList($db, $list, $frecencyInterval) {
        //Determine list order and create query
        if ($_SESSION['orderBy'] =='frecency') {
            $query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems ORDER BY calcfrec DESC";
        } else if ($_SESSION['orderBy'] == 'category') {
            $query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems JOIN Categories on ListItems.category = Categories.category ORDER BY Categories.viewOrder ASC, calcfrec DESC";
        } else {            
           $query = "SELECT * FROM ListItems ORDER BY title";           
        }
        //Get list from db
        try {
            $statement = $db->prepare($query);
            $statement->execute(array(':frecencyInterval'=>$frecencyInterval));
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
        $query = "SELECT *, (numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval as calcfrec FROM ListItems WHERE id=:id";
        $statement = $db->prepare($query);
        $statement->execute(array(':id'=>$id, ':frecencyInterval'=>$frecencyInterval));
        $listItem=$statement->fetch(PDO::FETCH_ASSOC);
        
        return $listItem;
    }
?>