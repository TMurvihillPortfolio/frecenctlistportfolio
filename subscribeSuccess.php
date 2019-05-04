<?php include 'php/config/session.php'; ?>
<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php 
    if (isset($_POST['continue'])) {
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <div class="outer">
        
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
                <h4>Congratulations!! You have successfully subscribed to our Premium Features!!</h4>               
                <br>
                <form action='subscribeSuccess.php' method='post'>
                    <button type="submit" name="continue" class='btn btn__secondaryDark'>Continue</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>