<?php  
    function getList($db) {
        //Determine list order and create query
        if ($_SESSION['orderBy'] =='frecency') {
            $query = "SELECT * FROM ListItems ORDER BY frecency DESC";
        } else if ($_SESSION['orderBy'] == 'category') {
            $query = "SELECT * FROM ListItems ORDER BY category";
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
        echo e;
        }
    }
?>