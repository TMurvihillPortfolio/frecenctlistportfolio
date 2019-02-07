window.onload = () => {
    
}
function prepareEnvironmentAddItemForm() {
    document.getElementById('js--addItemForm').style.display = 'block';
    document.getElementById('js--addItemOrderBy').style.display = 'none';
    document.getElementById('js--addItemFilterBy').style.display = 'none';
    document.getElementById('js--addItemListContainer').style.display = 'none';
}
function restoreEnvironmentAddItemForm() {
    document.getElementById('js--addItemForm').style.display = 'none';
    document.getElementById('js--addItemOrderBy').style.display = 'block';
    document.getElementById('js--addItemFilterBy').style.display = 'block';
    document.getElementById('js--addItemListContainer').style.display = 'block';
}

/***********************
 * CRUD operations on profiles
 * ********************/

function startChangeEmail(clickedItem) {
   
    var emailInput;
    var originalEmail;
    var changeSaveEmailButton;
    var cancelChangeEmailButton;

    emailInput = document.getElementById('js--emailInput');
    originalEmail = document.getElementById('js--profileOriginalEmail');
    changeSaveEmailButton = document.getElementById('js--profileChangeSaveEmail');
    cancelChangeEmailButton = document.getElementById('js--profileCancelEmail');

    if (clickedItem.innerText == "Change Email") { 
        emailInput.hidden = false;
        cancelChangeEmailButton.hidden = false;
        clickedItem.innerText = "Save";

    } else if (clickedItem.innerText == "Save") {
        emailInput.hidden = true;
        cancelChangeEmailButton.hidden = true;
        clickedItem.innerText = "Change Email";
        originalEmail.innerText=emailInput.children[1].value;
        changeSaveEmailButton.type = 'Submit';
        
    } else if (clickedItem.innerText == "Cancel") {
        emailInput.children[1].value = "";
        emailInput.hidden = true;
        cancelChangeEmailButton.hidden = true;
        changeSaveEmailButton.innerText = "Change Email";
        return;
    }
}
function startChangePassword(clickedItem) {
   
    var changePassword;
    var newPassword;
    var changePasswordButton;
    var cancelPasswordButton;

    changePassword = document.getElementById('js--profileChangePassword');
    newPassword = document.getElementById('js--profileNewPassword');
    changePasswordButton = document.getElementById('js--profileChangePasswordButton');
    cancelPasswordButton = document.getElementById('js--profileCancelPasswordButton');
    
    if (clickedItem.innerText == "Change Password") { 
        changePassword.hidden = false;
        cancelPasswordButton.hidden = false;
        clickedItem.innerText = "Save";

    } else if (clickedItem.innerText == "Save") {
        changePassword.hidden = true;
        cancelPasswordButton.hidden = true;
        clickedItem.innerText = "Change Password";
        document.getElementById('js--profileOriginalPassword').innerText=newPassword.value;
        changePasswordButton.type = 'Submit';
        return;

    } else if (clickedItem.innerText == "Cancel") {
        changePassword.children[0].value = "";
        changePassword.children[1].value = "";
        changePassword.children[2].value = "";
        changePassword.hidden = true;
        cancelPasswordButton.hidden = true;
        changePasswordButton.innerText = "Change Password";
        return;
    }
}
function startCloseAccount(clickedItem, userEmail) {
    //Get customer verification to close the Account   
    if (clickedItem.innerText == "Close Account") { 
        var yesNo = prompt('Close Account? Are you sure? This will delete your user account and all associated Piggy Banks and transactions. Please type your account email below to close the account.');
        if (!(yesNo == null) && !(yesNo =='')) {
            if (yesNo.toUpperCase() == userEmail.toUpperCase()) {
                clickedItem.type = 'submit';
            }else{
                alert("The email entered does not match the email associated with this account. Account has not been closed.");
            }
        }
    }  
}

/***********************
 * Mobile Navigation
 * ********************/
function mobileNav() {
    var mainNav = document.getElementById("js--mainNav");
    var mobileIcon = document.getElementById("js--mobileNavIcon");
    
    if (mobileIcon.classList.contains('fa-bars')) {
        mobileIcon.classList.remove('fa-bars');
        mobileIcon.classList.add('fa-window-close');
        mainNav.style.display = "block";
    }else{
        mobileIcon.classList.remove('fa-window-close');
        mobileIcon.classList.add('fa-bars');
        mainNav.style.display = "none";
    } 
} 

