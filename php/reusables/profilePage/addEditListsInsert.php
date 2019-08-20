<div class="profile_form profile__form--changeProfileButton">
    <button 
        class="
            btn 
            btn__secondary 
            profile__form--changeProfileButton
        "
    >
        <a href=" <?php 
            echo ($_SESSION['userInfo']['premium']) 
            ? 'addEditLists.php' 
            : ''; ?>">Add/Edit Lists<br>
            <span>(Premium)</span>
        </a>
    </button>                
</div>