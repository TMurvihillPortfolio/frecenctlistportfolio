<?php include 'php/config/session.php'; ?>
<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <div class="outer">
        
        <?php include 'php/reusables/mainnav.php'; ?>
        <section class="">
            <br>      
            
            <div class="">What do you buy frequently? What have you bought recently?</div>
            <!-- <h1>"My 'Frecent' List"</h1>
            <br>
            <hr>
            <br>
            <div class="login__whatIsFrecency">"We can select what we use the most right at the top of the list and then easily reorder the list by category when it is time to shop. It's so convenient!"
            </div>
            <div class="login__line1">
                <h3>Login<span> Page</span> </h3>
                <h4 class="login__line1--signup"><a href="signup.php">Easy Sign-up</a></h4>              
            </div>
            <p style="color: tomato;"><?php echo isset($result) ? $result : ''; ?></p>
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
                    <h4 class="login__line1--signup"><a href="signup.php">Easy Sign-up<a></h4> -->
                <!-- </div>
            </form> -->
        </section>



        <!-- <?php if (isset($result)) : ?>
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
                <h4>Congratulations!! You have successfully subscribed to our Premium Features!!</h4>               
                <br>
                <form action='subscribeSuccess.php' method='post'>
                    <button type="submit" name="continue" class='btn btn__secondaryDark'>Continue</button>
                </form>
            </div>
        </div> -->
    </div>
</body>
</html>