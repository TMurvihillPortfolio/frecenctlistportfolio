<div class="
    profile__form--changePassword 
    profile__form--changeProfileButton  
">
    <form action="profile.php" method="post">
        <div id="js--profileChangePassword" hidden>
            <input 
                type="password" 
                placeholder="enter current password" 
                name="userInputPassword"
            />
            <input 
                type="password" 
                placeholder="enter new password" 
                name="newPassword" 
                id="js--profileNewPassword"
            />
            <input 
                type="password" 
                placeholder="confirm new password" 
                name="confirmPassword"
            />
        </div>
        <div class="
            profile__form 
            profile__form--changePasswordButton
        ">
            <button 
                name="passwordSubmit" 
                type="button" 
                class="
                    btn 
                    btn__secondary 
                    profile__form--changeProfileButton
                " 
                onClick="startChangePassword(this)" 
                id='js--profileChangePasswordButton'
            >
                    Change Password
            </button>
            <button 
                name="passwordCancel" 
                type="button" 
                class="
                    btn 
                    btn__primaryVeryDark 
                    profile__form--changeProfileButton
                " 
                onClick="startChangePassword(this)" 
                id="js--profileCancelPasswordButton" 
                hidden
            >
                    Cancel
            </button>                 
        </div>                        
        <div 
            class="profile__form profile__form--password" 
            id="js--profileOriginalPassword" 
            hidden
        >
            <?php 
                if ($row['password']) {
                    echo $row['password'];
                }
            ?>
        </div>
    </form>
</div>