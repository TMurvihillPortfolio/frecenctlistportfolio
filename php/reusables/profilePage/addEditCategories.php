<!-- Add/Edit Categories -->
<div class="profile_form profile__form--changeProfileButton">
    <button 
        class="
            btn 
            btn__secondary 
            profile__form--changeProfileButton
        "
    >
        <a href='<?php 
            echo ($_SESSION['userInfo']['premium']) 
            ? 'editCategories.php' 
            : ''; ?>'>Add/Edit Categories<br>
            <span>(Premium, coming soon)</span>
        </a>
    </button>                
</div>