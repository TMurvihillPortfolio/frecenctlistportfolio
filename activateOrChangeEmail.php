<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php' ?>
<?php
    //if this is an email change from the profile page
    if(isset($_GET['userId']) && isset($_GET['newEmail'])) {
        $encoded_id = $_GET['userId'];
        $decode_id = base64_decode($encoded_id);
        $user_id_array = explode("encodeuserid", $decode_id);
        $userId = $user_id_array[1];

        $encoded_email = $_GET['newEmail'];
        $decode_email = base64_decode($encoded_email);
        $user_email_array = explode("encodenewemail", $decode_email);
        $newEmail = $user_email_array[1];
        
        try {
            $sql = "UPDATE users SET email =:newEmail WHERE userId=:userId";       
            $statement = $db->prepare($sql);
            $statement->execute(array(':newEmail' => $newEmail, ':userId' => $userId));
           
            if ($statement->rowCount() == 1) {
                $result = '<h2>Email Confirmed </h2>
                <p>Your new email address has been verified, you can now <a class="activate" href="logout.php" style="color: #e47587; font-style: italic">logout</a> and then login with your new email and old password.</p>';
            } else {
                $result = "<p class='lead'>An error occurred. Email has not been changed. :)</p>";
            }
        } catch(PDOException $ex) {
            $result = "An error occurred. ".$ex;
        }
    //if a new account
    } else if(isset($_GET['userId']) && !isset($_GET['newEmail'])) {
        $encoded_id = $_GET['userId'];
        $decode_id = base64_decode($encoded_id);
        $user_id_array = explode("encodeuserid", $decode_id);
        $userId = $user_id_array[1];
        
        try {
            $sql = "UPDATE users SET active =:active WHERE userId=:userId AND active='0'";       
            $statement = $db->prepare($sql);
            $statement->execute(array(':active' => "1", ':userId' => $userId));
           
            if ($statement->rowCount() == 1) {
                $result = '<h2>Email Confirmed </h2>
                <p>Your email address has been verified, you can now <a class="activate" href="index.php" style="color: #e47587; font-style: italic">login</a> with your email and password.</p>';
            } else {
                $result = "<p class='lead'>Account already activated. :)</p>";
            }
        } catch(PDOException $ex) {
            $result = "An error occurred. ".$ex;
        }
    //if get variables not found
    }else{
        $result="An error occurred, be sure to click on the link in the activation email to activate your account.";
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    
    <div class="profile__line1">
        <h2>Activation <span>Page</span></h2>       
    </div>        
    <?php if (isset($result)) : ?>
        <div class="signatureBox">
            <p style="color: tomato;"><?php echo isset($result) ? $result : ''; ?></p>
        </div>
    <?php endif; ?>
    <br>
    <br>
</body>
</html>