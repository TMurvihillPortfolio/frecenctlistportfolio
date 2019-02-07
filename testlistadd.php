<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php
    $listName = 'test1';
    $listUserId = 3;
    $isDefault = 0;

//insert first list
    $sqlInsert = "INSERT INTO Lists (listName, listUserId, isDefault)
    VALUES (:listName, :listUserId, :isDefault)";
    $statement = $db->prepare($sqlInsert);
    $statement->execute(array( ':listName' => $listName, ':listUserId' => $listUserId, ':isDefault' => $isDefault));
    if($statement->rowCount() == 1) {
        $result = "Registration Successful";
    } else {
        $result = "OOOPSA";
    }
    echo $result;