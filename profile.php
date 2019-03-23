<?php session_start(); ?>
<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php //update email
    if (isset($_POST['emailSubmit'])) {
        //initialize variables
        $email = ''; 
        $userId = '';
        $success = false;
        //sanitize user input
        if (isset($_POST['email']) && $_POST['email'] !=='') {
            $email = testInput($_POST['email']);
            $success = true;
        }
        //update email
        if (isset($_SESSION['userId']) && $success === true) {
            $userId = $_SESSION['userId'];
            try {
                $splQuery = "UPDATE users SET email = :email WHERE userId = :userId";
                $statement = $db->prepare($splQuery);
                $statement->execute(array(':userId'=>$userId, 'email'=>$email));
                if ($statement->rowCount() === 0) {
                    $result = "Update not successful. Please try logging out and logging in again.";
                }
            } catch (Exception $ex) {
                $result = "An error occurred: ".$ex;
            }
        } else {
            $result = "User not found or invalid email. Please try logging out and logging in again.";
        }           
    }
?>
<?php //update password
    try {
        if (isset($_POST['passwordSubmit'])) {
            //initialize variables
            $userId = $userInputPassword = $newPassword = $confirmPassword = '';
            //assign variables post and session values
            if (isset($_SESSION['userId'])) {
                $userId = $_SESSION['userId'];
            }
            if (isset($_POST['userInputPassword'])) {
                $userInputPassword = testInput($_POST['userInputPassword']);
            }
            if (isset($_POST['userInputPassword'])) {
                $newPassword = testInput($_POST['newPassword']);
            }
            if (isset($_POST['userInputPassword'])) {
                $confirmPassword = testInput($_POST['confirmPassword']);
            }
            //verifty that new and confirm new passwords match
            if ($newPassword === $confirmPassword) {
                //Get old hashed password from db
                $splQuery = "SELECT password FROM users WHERE userId = :userId";
                $statement = $db->prepare($splQuery);
                $statement->execute(array(':userId'=>$userId));
                //if user found
                if($row=$statement->fetch()){ 
                    //verify that old password matches db 
                    $passwordFromDb = $row['password'];                   
                    if (password_verify($userInputPassword, $passwordFromDb)) {
                        //hash the new password
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        //update db with new password
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
    } catch (Exception $ex) {
        $result = "An error occurred. Password may not be updated: ".$ex;
    }
?>
<?php //close account
    if (isset($_POST['closeAccountSubmit'])) {
        //delete user
        if (isset($_SESSION['userId'])) {
            $result = closeAccount($db, $_SESSION['userId']);
            //clean up environment
            if ($result === 'Account has been closed.') {
                logout();         
            }
        } else {
            $result = "Account not closed. User not found, please try logging in and logging out again.";
        }
    }
?>
<?php //get profile data for page render
    try {
        if (isset($_SESSION['userId']) && $_SESSION['userId'] !== '') {
            $userId = $_SESSION['userId'];
            $splQuery = "SELECT * FROM users WHERE userId = :userId";
            $statement = $db->prepare($splQuery);
            $statement->execute(array(':userId'=>$userId));
    
            if ($row=$statement->fetch()) {  
                $userId = $row['userId'];
            } else {
                $result = 'User not found, please login again or signup for a new account.';
            }
        } else {
            $result = 'User not found, please login again or signup for a new account.';
        }
    } catch (Exception $e) {
        $result = "An error occurred. Please try logging out and logging back in.";
    } 
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <div class="outer">
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
                    if (isset($userId)) {
                        echo "My Profile";
                    } else {
                        echo "no user found";
                    }
                ?>
            </div>
            <div class="profile__container--bottom">
                <div class="profile_form profile__form--changeProfileButton">
                    <button class="btn btn__secondary profile__form--changeProfileButton">Add/Edit Categories (Premium)</button>                
                </div>
                <br>
                <div class="profile_form profile__form--changeProfileButton">
                    <button class="btn btn__secondary profile__form--changeProfileButton">Add, Edit, or Delete Lists (Premium)</button>                
                </div>
                <br>
                <form action="profile.php" method="post">
                    <div id="js--emailInput" hidden>
                        <div style="display: flex">
                            <h3 name="NOT YET IMPLEMENTED">Change Email:</h3>
                        </div>
                        <input name="email" type="email" placeholder="enter new email">
                    </div>
                    <div class="profile_form profile__form--changeProfileButton">                             
                        <button name="emailSubmit" type="button" class="btn btn__secondary profile__form--changeProfileButton" onClick="startChangeEmail(this)" id="js--profileChangeSaveEmail">Change Email</button>
                        <button name="emailCancel" type="button" class="btn btn__primaryVeryDark profile__form--changeProfileButton" onClick="startChangeEmail(this)" id="js--profileCancelEmail" hidden>Cancel</button>                 
                    </div>
                </form> 
                <br>                                         
                                
                <div class="profile__form--changePassword profile__form--changeProfileButton">
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
                
                
            </div>
        </div>
    </div>
</body>
</html>