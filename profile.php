<?php session_start(); ?>
<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php //update email
    if (isset($_POST['emailSubmit'])) {
        $email = $_POST['email'];
        $userId = $_SESSION['userId'];
        $splQuery = "UPDATE users SET email = :email WHERE userId = :userId";
        $statement = $db->prepare($splQuery);
        $statement->execute(array(':userId'=>$userId, 'email'=>$email));
    }
?>
<?php //update password
    if (isset($_POST['passwordSubmit'])) {
        $userId = $_SESSION['userId'];
        $userInputPassword = $_POST['userInputPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];
        
        if ($newPassword === $confirmPassword) {
            //Get old hashed password from db
            $splQuery = "SELECT password FROM users WHERE userId = :userId";
            $statement = $db->prepare($splQuery);
            $statement->execute(array(':userId'=>$userId));

            if($row=$statement->fetch()){  
                $passwordFromDb = $row['password'];
                
                if (password_verify($userInputPassword, $passwordFromDb)) {
                    $result="password is good";
                    //hash the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    //update db
                    $sqlUpdate = "UPDATE users SET password=:password WHERE userId=:userId";
                                $statement = $db->prepare($sqlUpdate);
                                $statement->execute(array(':password'=>$hashedPassword, ':userId'=>$userId));

                                if($statement->rowCount()===1){
                                    $result = "Password reset Successful.";
                                }else{
                                    $result = 'Password not updated.';
                                }
                } else {
                    $result="Current password is not correct.";
                }
            }
        } else {
            $result = "New password and confirm password do not match.";
        }
    }
?>
<?php //close account
    if (isset($_POST['closeAccountSubmit'])) {
        $result = closeAccount($db, $_SESSION['userId']);
        header('Location: index.php');
    }
?>
<?php //get profile data for page render
    $userId = $_SESSION['userId'];
    $splQuery = "SELECT * FROM users WHERE userId = :userId";
    $statement = $db->prepare($splQuery);
    $statement->execute(array(':userId'=>$userId));

    if($row=$statement->fetch()){  
        $email = $row['email'];
    }else{
        $result = 'User not found, please login again or signup for a new account.';
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <?php include 'php/reusables/mainnav.php'; ?>
    <div class="profile__line1">
        <h2>Profile<span>Page</span></h2>
    </div>        
    <?php if (isset($result)) : ?>
        <div class="signatureBox">           
            <p style="color: tomato;"><?php echo isset($result) ? $result : ''; ?></p>
        </div>
    <?php endif; ?>  
    <div class="profile__container">
        <div class="profile__form profile__form--email" id="js--profileOriginalEmail">
            <?php 
                if (isset($email)) {
                    echo $email;
                } else {
                    echo "no email found";
                }
            ?>
        </div>
        <div class="profile__container--bottom">
            <form action="profile.php" method="post">
                <div id="js--emailInput" hidden>
                    <div style="display: flex">
                        <h3 name="NOT YET IMPLEMENTED" style="color: #D9AE5C"><span style="color: #e47587">Piggy Says: </span>This website is under construction and eventually a verification email will be sent when the account email is changed.</h3>
                    </div>
                    <input name="email" type="email" placeholder="enter new email">
                </div>
                <div class="profile_form profile__form--changeProfileButton">                             
                    <button name="emailSubmit" type="button" class="btn btn__secondary profile__form--changeProfileButton" onClick="startChangeEmail(this)" id="js--profileChangeSaveEmail">Change Email</button>
                    <button name="emailCancel" type="button" class="btn btn__primaryVeryDark profile__form--changeProfileButton" onClick="startChangeEmail(this)" id="js--profileCancelEmail" hidden>Cancel</button>                 
                </div>
            </form> 
            <br>                                         
                            
            <div class="profile__form--changePassword">
                <form action="profile.php" method="post">
                    <div id="js--profileChangePassword" hidden>
                        <input type="password" placeholder="enter current password" name="userInputPassword">
                        <input type="password" placeholder="enter new password" name="newPassword" id="js--profileNewPassword">
                        <input type="password" placeholder="confirm new password" name="confirmPassword">
                    </div>
                    <div class="profile__form profile__form--changePasswordButton">
                        <button name="passwordSubmit" type="button" class="btn btn__secondary profile__form--changeProfileButton" onClick="startChangePassword(this)" id='js--profileChangePasswordButton'>Change Password</button>
                        <button name="passwordCancel" type="button" class="btn btn__primaryVeryDark profile__form--changeProfileButton" onClick="startChangePassword(this)" id="js--profileCancelPasswordButton" hidden>Cancel</button>                 
                    </div>
                    
                    <div class="profile__form profile__form--password" id="js--profileOriginalPassword" hidden>
                        <?php 
                            if ($row['password']) {
                                echo $row['password'];
                            }
                        ?>
                    </div>
                </form>
            </div>    
            <br>
            <form action="profile.php" method="post">              
                <div class="profile_form profile__form--changeProfileButton">                             
                    <button name="closeAccountSubmit" type="button" class="btn btn__secondary profile__form--changeProfileButton" onClick="startCloseAccount(this, '<?php echo $row['email']; ?>')" id="js--profileCloseAccount">Close Account</button>
                </div>
            </form>
            <br>
            <div class="profile_form">
                <a class="btn btn__secondary profile__form--addEditPiggyBanks" href="addEditPiggyBanks.php">Add, Edit, or Delete Piggy Banks</a>                
            </div>
            <br>
        </div>
    </div>
</body>
</html>