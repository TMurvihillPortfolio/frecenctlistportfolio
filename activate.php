<?php include 'php/config/config.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php' ?>
<?php
    if(isset($_GET['userId'])) {
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
    }else{
        $result="An error occurred, be sure to click on the link in the activation email to activate your account.";
    }

?>
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