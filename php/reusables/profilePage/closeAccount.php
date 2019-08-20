<!-- Close Account -->
<form action="profile.php" method="post">              
    <div class="
        profile_form 
        profile__form--changeProfileButton
    ">                             
        <button 
            name="closeAccountSubmit" 
            type="button" 
            class="
                btn 
                btn__secondary 
                profile__form--changeProfileButton
            " 
            onClick="startCloseAccount(this, '<?php echo $row['email']; ?>')" 
            id="js--profileCloseAccount"
        >
            Close Account
        </button>
    </div>
</form>