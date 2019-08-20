<!-- enroll/cancel premium subscription -->
<form action="profile.php" method="post">              
    <div class="profile_form profile__form--changeProfileButton">
        <!-- Cancel Premium button -->                        
        <button 
            name="cancelPremiumSubscription" 
            type="button" 
            class="btn btn__secondary profile__form--changeProfileButton" 
            style="<?php 
                echo ($_SESSION['userInfo']['premium'])
                ? '' 
                : 'display: none'; 
            ?>" 
            id='js--cancelPremium' 
            onClick="cancelPremium('<?php 
                    echo $_SESSION["userInfo"]["email"]; 
                ?>'
            );"
        >
            Cancel Premium Subscription
        </button>
        <!-- Go premium button -->
        <button 
            type="button" 
            class="btn btn__secondary profile__form--changeProfileButton" 
            style="<?php 
                echo ($_SESSION['userInfo']['premium'])
                ? 'display: none' 
                : ''; 
            ?>" 
        >
            <a href='subscribe.php'>Go Premium!</a>
        </button>
    </div>
</form>