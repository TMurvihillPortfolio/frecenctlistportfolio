<section class="mainNav">
    <a href="index.html"><img class="mainNav__logo" src="img/logo.gif" alt="Tech Logo"></a>
    <ul class="mainNav__nav" id="js--mainNav">
        <li class="mainNav__nav--item">
            <a href="index.php">Home</a>
        </li>
        <?php if(isset($_SESSION['userId']) && !$_SESSION['userId'] == '') : ?>                  
            <li class="mainNav__nav--item">
                <a href="profile.php">View Profile</a>
            </li>
            <?php if (!$_SESSION['userInfo']['premium']) : ?>
                <li class="mainNav__nav--item">
                    <a href="subscribe.php">Go Premium!</a>
                </li>
            <?php endif; ?>
            <li class="mainNav__nav--item">
                <a href="about.php">About</a>
            </li>
            <li class="mainNav__nav--item">
                <a href="logout.php">Logout</a>
            </li>
        <?php else : ?>     
            <li class="mainNav__nav--item">
                <a href="signup.php">Signup</a>
            </li>
            <li class="mainNav__nav--item">
                <a href="index.php">Login</a>
            </li>
            <li class="mainNav__nav--item">
                <a href="subscribe.php">Go Premium!</a>
            </li>
            <li class="mainNav__nav--item">
                <a href="about.php">About</a>
            </li>           
        <?php endif; ?>
    </ul>
</section>