<?php include 'php/config/session.php'; ?>
<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php //sign-up
    try {
        $query = 'UPDATE users SET premium = true WHERE userId = :userId';
        $statement = $db->prepare($query);
        $statement->execute(array(':userId'=>$_SESSION['userInfo']['userId']));
        $_SESSION['userInfo']['premium']='1';
    } catch (Exception $e) {
        //NOT YET IMPLEMENTED
        $result = "An error occurred. If premium features not enabled, please contact customer support.";
    }
    header('Location: subscribeSuccess.php');   
?>