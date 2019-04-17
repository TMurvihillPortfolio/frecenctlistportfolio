<?php include 'php/config/config.php'; ?>
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
                <p style="color: tomato;"><?php echo isset($result) ? $result : ''; ?></p>
            </div>
        <?php endif; ?>
        <div class="signup signatureBox">
            <div class="signup__line1">
                <h2><span style="color: #3C7496">About</span>Page
                </h2>
            </div>        
            <form action="signup.php" method="post" class="signup__form">                            
                <div style="font-weight: 400;">Website designed and developed by Tisha Murvihill <a href="mailto:tech@take2tech.ca">tech@take2tech.ca</a>. <br><br>The 'Frecency List' uses a 'frecency' algorhythm developed by Tisha Murvihill. Wikipedia defines frecency as "any heuristic that combines the frequency and recency into a single measure." <br><br><span style="font-size: 16px">Tech Specs -- HTML, CSS, Vanilla JS with PHP(PDO) and SQL.Images served by Cloudinary content delivery network. Subscriptions use a Stripe/PHP online store implementation.</span></div>               
            </form>
        </div>
    </div>
</body>
</html>