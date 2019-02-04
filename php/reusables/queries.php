<?php  
    function getList($db) {
        //Determine list order and create query
        if ($_SESSION['orderBy'] =='frecency') {
            $query = "SELECT *, numClicks*10000/(lastClick-firstClick) as calcfrec FROM ListItems ORDER BY calcfrec DESC";
        } else if ($_SESSION['orderBy'] == 'category') {
            $query = "SELECT * FROM ListItems ORDER BY category ASC, frecency DESC";
        } else {            
           $query = "SELECT * FROM ListItems ORDER BY title";           
        }

        //Get list from db
        try {
            $statement = $db->prepare($query);
            $statement->execute();
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

    function getListItemById($db, $id) {
        $query = "SELECT *, numClicks*10000/(lastClick-firstClick) as calcfrec FROM ListItems WHERE id=:id";
        $statement = $db->prepare($query);
        $statement->execute(array(':id'=>$id));
        $listItem=$statement->fetch(PDO::FETCH_ASSOC);
        
        return $listItem;
    }
?>