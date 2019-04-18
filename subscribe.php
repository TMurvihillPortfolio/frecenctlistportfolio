<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php //sign-up
    if (isset($_POST['signupBtn'])) {
        //double-check session variables are cleared
        if (isset($_SESSION['userId'])) {
            session_start();
            logout();
        }
        //collect form data and store in variables       
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $listName = $_POST['listName'];
        $isDefault = TRUE;
        $listUserId = NULL;

        //check password length
        if (!validatePassword($password)) {
            $result = 'Password must be at least 8 characters in length.';
            unset($_POST['signupBtn']);
            return;
        }
        //check that password and confirm the password match
        if (!$password === $confirmPassword) {
            $result = "Passwords do not match. Please try again.";
            unset($_POST['signupBtn']);
            return;
        }        
        //check if email + name exists              
        try {
            $sqlQuery = "SELECT * FROM users WHERE email= :email";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':email'=> $email));
        } catch (Exception $ex) {
            $result = "An error occurred: ".$ex;
            unset($_POST['signupBtn']);
            return;
        }       
        if (!$listUserRow = $statement->fetch()) {                   
            try {               
                //insert user
                $sqlInsert = "INSERT INTO users (email, password)
                VALUES (:email, :password)";
                $statement = $db->prepare($sqlInsert);
                $statement->execute(array(':email' => $email, ':password' => $hashed_password));                  

                if($statement->rowCount() == 1){
                    $listUserId = $db->lastInsertId();
                    $encodeUserId = base64_encode("encodeuserid{$listUserId}");
                
                    //insert first list into list table
                    try {               
                        //insert first list
                        $sqlInsert = "INSERT INTO Lists (listName, listUserId, isDefault)
                        VALUES (:listName, :listUserId, :isDefault)";
                        $statement = $db->prepare($sqlInsert);
                        $statement->execute(array( ':listName' => $listName, ':listUserId' => $listUserId, ':isDefault' => $isDefault));
                        if($statement->rowCount() == 1) {
                            $result = "Registration Successful";
                        } else {
                            throw new Exception('Error adding List');
                        }
                    } catch (PDOException $ex) {
                        $result = "An error occurred entering your first list: ".$ex;
                    }

                    //prepare email body

                    $mail_body = '<html>
                        <body style="color:#083a08; font-family: Lato, Arial, Helvetica, sans-serif;
                                            line-height:1.8em;">
                        <h2>Message from Frecency<span style="color:#3C7496;">List</span></h2>
                        <p>Dear Frecency List user,<br><br>Thank you for registering, please click on the link below to
                            confirm your email address</p>
                        <p style="text-decoration: underline; font-size: 24px;"><a style="color:#3C7496;" href='.$rootDirectory.'activateOrChangeEmail.php?userId='.$encodeUserId.'"> Confirm Email</a></p>
                        <p><strong>&copy;2018 <a href="https://take2tech.ca" style="color:#3C7496;text-decoration: underline;">take2tech.ca</strong></p>
                        </body>
                        </html>';
                    
                    $subject = "Message from 'Frecency' List";
                    $headers = "From: 'Frecency' List.--User Signup" . "\r\n";
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    
                    //Error Handling for PHPMailer
                    if(!mail($email, $subject, $mail_body, $headers)){
                        $result = "Email send failed.";
                    }
                    else{
                        $result = "Registration Successful. Please check email for confirmation link.";
                    }
                }
            } catch (PDOException $ex) {
                $result = "An error occurred entering a new user: ".$ex;
            }          
        }else{
            $result="Account email already exists. Please use another email.";
        }        
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <div class="outer">
        <?php include 'php/reusables/mainnav.php'; ?>
        <?php if (isset($result)) : ?>
            <div class="signatureBox">
                <p style="color: tomato;"><?php echo isset($result) ? $result : ''; ?></p>
            </div>
        <?php endif; ?>
        <div class="subscribe signatureBox">
            <div class="subscribe__line1">
                <h2>Easy&nbsp;&nbsp;<span>Subscribe</span>Page
                </h2>
                <h3>Premium Subscription Includes:</h3>
                <br>
                <hr>
                <br>
                <ul>
                    <li><img src="img/favicon.png">Keep track of Multiple Lists</li>
                    <li><img src="img/favicon.png">Create your own categories</li>
                    <li><img src="img/favicon.png">Multiple Users can access lists</li>
                </ul>
                <br>
                <h4>(83¢ a month!)
                <h3>$10.00US per year</h3>
                <br>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="7QMYYWD8WJPL2">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>             
        </div>
    </div>
</body>
</html>