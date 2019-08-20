<?php include 'php/config/config.php'; ?>
<?php include 'php/config/session.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>

<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <div class="outer">
        <?php include 'php/reusables/mainnav.php'; ?>
        <?php if (isset($result)) : ?>
            <div class="signatureBox">
                <p style="color: tomato;">
                    <?php echo isset($result) ? $result : ''; ?>
                </p>
            </div>
        <?php endif; ?>
        <div class="about signatureBox">
            <div class="about__line1">
                <h1><span>About</span>Page
                </h1>
            </div>        
            <form 
                action="signup.php" 
                method="post" 
                class="signup__form"
            >                            
                <div class="about__text">
                    Website designed and developed by Tisha Murvihill <a href="mailto:tech@take2tech.ca">tech@take2tech.ca</a>. 
                    <br><br>The 'Frecent List' uses a 'frecency' algorhythm developed by Tisha Murvihill. Wikipedia defines frecency as "any heuristic that combines the frequency and recency into a single measure." <br><br>
                    <span style="font-size: 16px">Tech Specs -- HTML, CSS, Vanilla JS with PHP(PDO) and SQL. Subscriptions powered by PayPal.</span>
                </div>               
            </form>
        </div>
    </div>
</body>
</html>