<?php include 'php/config/config.php'; ?>
<?php include 'php/config/session.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php //update email
    if (isset($_POST['emailSubmit'])) {
        //initialize variables
        $newEmail = '';
        $userId = '';
        $success = false;

        //double check and set user id
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
        } else {
            $result = "Session user Id was lost. Please logout and login again.";
            exit();
        }

        //sanitize user input
        if (isset($_POST['email']) && $_POST['email'] !=='') {
            $newEmail = testInput($_POST['email']);
            $success = true;
        }

        //encode user id and email
        $encodeUserId = base64_encode("encodeuserid{$userId}");
        $encodeNewEmail = base64_encode("encodenewemail{$newEmail}");

        //prepare email body

        $mail_body = '<html>
        <body style="color:#083a08; font-family: Lato, Arial, Helvetica, sans-serif;
                            line-height:1.8em;">
        <h2>Message from Frecency<span style="color:#3C7496;">List</span></h2>
        <p>Dear Frecency List user,<br><br>Thank you for requesting to change your account email address, please click on the link below to
            confirm your new email address</p>
        <p style="text-decoration: underline; font-size: 24px;"><a style="color:#3C7496;" href='.$rootDirectory.'activateOrChangeEmail.php?userId='.$encodeUserId.'&newEmail='.$encodeNewEmail.'"> Confirm Email</a></p>
        <p><strong>&copy;2018 <a href="https://take2tech.ca" style="color:#3C7496;text-decoration: underline;">take2tech.ca</strong></p>
        </body>
        </html>';

        $subject = "Message from 'Frecency' List";
        $headers = "From: 'Frecency' List.--User Signup" . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        //Error Handling for PHPMailer
        if(!mail($newEmail, $subject, $mail_body, $headers)){
            $result = "Email confirmation send failed.";
        }
        else{
            $result = "Email update pending confirmation. Please check new email for confirmation link.";
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
<?php //cancel premium subscription
    if (isset($_POST['cancelPremiumSubscription'])) {
        //delete user
        if (isset($_SESSION['userInfo'])) {
            try {
                $query = 'UPDATE users SET premium = false WHERE userId = :userId';
                $statement = $db->prepare($query);
                $statement->execute(array(':userId'=>$_SESSION['userInfo']['userId']));
                $result = "Your premium subscription has been cancelled. You may still use the Frecency List at the basic level.";
    
            } catch (Exception $e) {
                //NOT YET IMPLEMENTED
                $result = 'Cancel subscription failed. Please try logging out and logging in again.';
            }
        } else {
            $result = "Subscription not cancelled. User not found, please try logging out and logging in again.";
        }
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
            $result = "Account not closed. User not found, please try logging out and logging in again.";
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
                <p style="color: #153a52;;"><?php echo isset($result) ? $result : ''; ?></p>
            </div>
        <?php endif; ?>  
        <div class="profile__container">
            <div class="profile__form profile__form--email" id="js--profileOriginalEmail">
                <?php 
                    if (isset($userId)) {
                        echo $row['email'];
                    } else {
                        echo "no user found";
                    }
                ?>
            </div>
            <div class="profile__container--bottom">
                <div class="profile_form profile__form--changeProfileButton">
                    <button class="btn btn__secondary profile__form--changeProfileButton"><a href='<?php echo ($_SESSION['userInfo']['premium']) ? 'editCategories.php' : ''; ?>'>Add/Edit Categories <span>(Premium)</span></a></button>                
                </div>
                <br>
                <div class="profile_form profile__form--changeProfileButton">
                    <button class="btn btn__secondary profile__form--changeProfileButton"><a href=" <?php echo ($_SESSION['userInfo']['premium']) ? 'addEditLists.php' : ''; ?>">Add, Edit, or Delete Lists <span>(Premium)</span><a></button>                
                </div>
                <br>
                <form action="profile.php" method="post">
                    <div id="js--emailInput" hidden>
                        <div style="display: flex">
                            <h3>Change Email:</h3>
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
                        <button name="cancelPremiumSubscription" type="button" class="btn btn__secondary profile__form--changeProfileButton" id='js--cancelPremium' onClick="cancelPremium('<?php echo $_SESSION["userInfo"]["email"]; ?>');">Cancel Premium Subscription</button>
                    </div>
                </form>
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