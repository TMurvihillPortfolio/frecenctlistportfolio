<!-- Change Email -->
<form action="profile.php" method="post">
    <div id="js--emailInput" hidden>
        <div style="display: flex">
            <h3>Change Email:</h3>
        </div>
        <input 
            name="email" 
            type="email" 
            placeholder="enter new email"
        />
    </div>
    <div class="profile_form profile__form--changeProfileButton">                             
        <button 
            name="emailSubmit" 
            type="button" 
            class="
                btn 
                btn__secondary 
                profile__form--changeProfileButton
            " 
            onClick="startChangeEmail(this)" 
            id="js--profileChangeSaveEmail"
        >
            Change Email
        </button>
        <button 
            name="emailCancel" 
            type="button" 
            class="
                btn 
                btn__primaryVeryDark 
                profile__form--changeProfileButton
            " 
            onClick="startChangeEmail(this)" 
            id="js--profileCancelEmail" 
            hidden
        >
            Cancel
        </button>                 
    </div>
</form>