<?php include 'php/config/session.php'; ?>
<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php //sign-up for Premium
    //set premium to true on user id
    try {
        $query = 'UPDATE users SET premium = true WHERE userId = :userId';
        $statement = $db->prepare($query);
        $statement->execute(array(':userId'=>$_SESSION['userInfo']['userId']));
        $_SESSION['userInfo']['premium']='1';
    } catch (Exception $e) {
        //NOT YET IMPLEMENTED
        $result = "An error occurred. If premium features not enabled, please contact customer support.";
    }

    //create custom category table for user
    $custCatList = "cat1, cat2, cat3, cat4, cat5";
    try {
        $query = "INSERT INTO customCategories (custCatUserId, custCatList)
                            VALUES (:custCatUserId, :custCatList);";
        $statement = $db->prepare($query);
        $statement->execute(array(
                            ':custCatUserId'=>$_SESSION['userInfo']['userId'],
                            ':custCatList' => $custCatList
        ));
    } catch (Exception $e) {
        //NOT YET IMPLEMENTED
        $result = "Something went wrong with custom category functionality. Please contact support.";
    }

    //show success page
    header('Location: subscribeSuccess.php');   
?>