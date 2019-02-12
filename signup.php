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
                        <body style="color:#FFCE00; font-family: Lato, Arial, Helvetica, sans-serif;
                                            line-height:1.8em;">
                        <h2>Message from Frecency<span style="color:#BCB5D7;">List</span></h2>
                        <p>Dear Frecency List user,<br><br>Thank you for registering, please click on the link below to
                            confirm your email address</p>
                        <p style="text-decoration: underline; font-size: 24px;"><a style="color:#BCB5D7;" href='.$rootDirectory.'activate.php?userId='.$encodeUserId.'"> Confirm Email</a></p>
                        <p><strong>&copy;2018 <a href="https://take2tech.ca" style="color:#BCB5D7;text-decoration: underline;">take2tech.ca</strong></p>
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
    <?php include 'php/reusables/mainnav.php'; ?>
    <?php if (isset($result)) : ?>
        <div class="signatureBox">
            <p style="color: tomato;"><?php echo isset($result) ? $result : ''; ?></p>
        </div>
    <?php endif; ?>
    <div class="signup signatureBox">
        <div class="signup__line1">
            <h3>Easy&nbsp;&nbsp;<span>Signup</span>Page
            </h3>
        </div>        
        <form action="signup.php" method="post" class="signup__form">                            
            <div class="signup__form--email">
                <label for="email">Email: </label>
                <input name="email" type="email" placeholder="A confirmation email will appear in your inbox." required> 
            </div>                 
            <div class="signup__form--password">
                <label for="password">Password: </label>
                <input name="password" type="password" value='password' required>                
            </div>
            <div class="signup__form--password">
                <label for="confirmPassword">Confirm Password: </label>
                <input name="confirmPassword" type="password" value='password' required>                
            </div>
            <div class="signup__form--listName">
                <label for="listName">List Name: </label>
                <input name="listName" type="text" value='My List' placeholder="For example 'shopping' or 'todo'" >
            </div>
            <div class="signup__form--submit">
                <input type="submit" name="signupBtn" class="btn" value="Submit"/>
            </div>
        </form>
    </div>
</body>
</html>