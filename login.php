<?php include 'php/config/config.php'; ?>
<?php include 'php/config/session.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php //on login submit button
    if(isset($_POST['submitLogin'])){  
        
        $inputPassword = $_POST['password']; 
        $inputEmail = $_POST['email'];       
        try {
            $splQuery = "SELECT * FROM users WHERE email = :email";
            $statement = $db->prepare($splQuery);
            $statement->execute(array(':email'=>$inputEmail));

            if($row=$statement->fetch()){
                $userId = $row['userId'];
                $hashed_password = $row['password'];
                $password = $row['password'];
                $activated = $row['active'];

                if(password_verify($inputPassword, $hashed_password)){
                    //echo "did we visit";
                    if ($activated | !$activated) {
                        //clear old session
                        if (isset($_SESSION['userInfo']['userId'])) {
                            unset($_SESSION['userInfo']);
                            unset($_SESSION['listId']);
                            unset($_SESSION['orderBy']);
                            unset($_SESSION['viewBy']);
                            unset($_SESSION['userId']); //left over from early versions

                            // NOT YET IMPLEMENTED if(isset($_COOKIE['rememberUserCookie'])){
                            //     uset($_COOKIE['rememberUserCookie']);
                            //     setcookie('rememberUserCookie', null, -1, '/');
                            // } 
                        }
                        //Not yet implemented, use session userInfo instead of user id 
                        $_SESSION['userId'] = $userId;

                        //store user info in session
                        $_SESSION['userInfo'] = $row;             

                        if (isset($_POST['listSelect'])) {
                            $splQuery = "SELECT * FROM Lists WHERE listId = :listId";
                            $statement = $db->prepare($splQuery);
                            $statement->execute(array(':listId'=>$_SESSION['listId']));
                            if($listRow=$statement->fetch()){              
                                header("Location: index.php");
                                exit;
                            } else {
                                //NOT YET IMPLEMENTED error handling
                                $result = "list not found";
                            }
                        }else{
                            $splQuery = "SELECT * FROM Lists WHERE listUserId = :userId AND isDefault = 1";
                            $statement = $db->prepare($splQuery);
                            $statement->execute(array(':userId'=>$userId));
                            if($listRow=$statement->fetch()){
                                $_SESSION['listId'] = $listRow['listId'];                
                                header("Location: index.php");
                                exit;
                            } else {
                                //NOT YET IMPLEMENTED error handling
                                $result = "default list not found";
                            }
                        }
                        
                        
                    }else{
                        $result="Account not activated. Please check your email inbox for a verification email.";
                    }
                }else{           
                    $result = "Invalid password.<br>Please try again.";
                }
                    
            }else{
                $result = "Email not found.<br>Please try again.";
            }

        } catch (PDOException $ex) {
            $result = "An error occurred.<br>Error message number: ".$ex->getCode();
        }  
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <div class="outer">
        <?php include 'php/reusables/mainnav.php'; ?>
        <section class="login">
            <br>      
            
            <div class="login__superHeading"><p>What do you buy frequently? <span>plus</span> What have you bought recently?</p></div>
            <h1>"My 'Frecent' List"</h1>
            <br>
            <hr>
            <br>
            <div class="login__whatIsFrecency">"It's so convenient! We can select what we use the most right at the top of the list and then easily reorder the list by category when it is time to shop." --Trish Hill
            </div>

            <div class="signatureBox">
                
                <div class="login__line1">
                    <h3>Login<span> Page</span> </h3>
                    <h4 class="login__line1--signup"><a href="signup.php">Easy Sign-up</a></h4>              
                </div>
                <?php include 'php/reusables/messageToUser.php'; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="login__form">
                                                    
                    <div class="login__form--email">
                        <label for="email">Email: </label>
                        <input name="email" type="email" required>                                           
                    </div>
                                
                    <div class="login__form--password">
                        <label for="password">Password: </label>
                        <input name="password" type="password" placeholder="password" required>                
                    </div>
                
                    <div class="login__form--submit">
                        <input type="submit" name="submitLogin" class="btn" value="Submit"/>           
                        <!-- <h4 class="login__line1--signup"><a href="signup.php">Easy Sign-up<a></h4> -->
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>
</html>