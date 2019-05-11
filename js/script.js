window.onload = () => {
    //scroll down to a location if necessary
    const scrollPositionDiv = document.querySelector('#js--scrollPositionDiv');
    if (scrollPositionDiv && parseInt(scrollPositionDiv.innerText) > 0) {
        window.scrollTo(0, parseInt(scrollPositionDiv.innerText));
        scrollPositionDiv.innerText = "0";
    }



    //choose mobile or main Nav
    function prepareNavEnviroment() {

        console.log('imindocreadynav')
        const mainNav = document.querySelector("#js--mainNav");
        const mobileIcon = document.querySelector("#js--mobileNavIcon");
        const closeIcon = document.querySelector("#js--closeIcon");
        if (window.innerWidth>599) {
            console.log('docready>greaterthan599');
            mobileIcon.classList.remove('displayBlock');
            mobileIcon.classList.add('displayNone');       
            closeIcon.classList.remove('displayBlock');  
            closeIcon.classList.add('displayNone');
            mainNav.classList.remove('displayBlock');
            mainNav.classList.remove('displayNone');
            mainNav.classList.add('flex');
        } else {
            console.log('docready<lesthan599');
            mobileIcon.classList.remove('displayNone');
            mobileIcon.classList.add('displayBlock');       
            closeIcon.classList.remove('displayBlock');  
            closeIcon.classList.add('displayNone');
            mainNav.classList.remove('displayBlock');
            mainNav.classList.remove('flex');
            mainNav.classList.add('displayNone');
        }
    }
    window.addEventListener("resize", prepareNavEnviroment);
    prepareNavEnviroment();
}
function prepareEnvironmentAddItemForm() {
    document.getElementById('js--addItemButton').style.display ='none';
    document.getElementById('js--addItemForm').style.display = 'block';
    document.getElementById('js--addItemOrderBy').style.display = 'none';
    document.getElementById('js--addItemFilterBy').style.display = 'none';
    document.getElementById('js--addItemListContainer').style.display = 'none';
}
function restoreEnvironmentAddItemForm() {
    document.getElementById('js--addItemButton').style.display ='block';
    document.getElementById('js--addItemForm').style.display = 'none';
    document.getElementById('js--addItemOrderBy').style.display = 'block';
    document.getElementById('js--addItemFilterBy').style.display = 'block';
    document.getElementById('js--addItemListContainer').style.display = 'block';
}
function verifyDeleteItem(item) {
    const listItem = item.parentElement.previousElementSibling.children[2].children[0].value;
    const yesNo = confirm(`Delete item: ${listItem}?`);
    if (yesNo) {
        const scrollPositionInput = item.nextElementSibling;
        scrollPositionInput.value = parseInt(window.pageYOffset).toString();
        item.type = 'submit';
    }
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
function cancelPremium(email) {
    const cancelPremiumButton = document.querySelector('#js--cancelPremium');
    const confirmEmail = prompt('Cancel Premuim Subscription? Are you sure? This will delete all your lists except your default list. Only one user will be able to access your list. Please confirm by typing in your account email address below:');
    if (confirmEmail==email) {
        cancelPremiumButton.type="submit";
    }
}
function startCloseAccount(clickedItem, userEmail) {
    //Get customer verification to close the Account   
    if (clickedItem.innerText == "Close Account") { 
        var yesNo = prompt('Close Account? Are you sure? This will delete your user account and all associated lists and list items. Please type your account email below to close the account.');
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
 * CRUD operations on lists
 ***********************/

 function startAddList(clickedItem) {

    // if (clickedItem.innerText == "Add List") {
    //     emailInput.hidden = true;
    //     cancelChangeEmailButton.hidden = true;
    //     clickedItem.innerText = "Change Email";
    //     originalEmail.innerText=emailInput.children[1].value;
    //     changeSaveEmailButton.type = 'Submit';
        
    if (clickedItem.innerText == "Cancel") {
        console.log(clickedItem);
        clickedItem.parentElement.previousElementSibling.children[1].checked = false;
        clickedItem.parentElement.previousElementSibling.previousElementSibling.children[1].value = "";   
        return;
    }
 }

 function startEditLists(clickedItem) {
    //declare variables

    var listName;
    var listNameEdit;
    var isDefault;
    var isDefaultEdit;
    
    var cancelButton;
    var ancestorDiv;

    //define DOM elements
        
    ancestorDiv = clickedItem.parentElement.parentElement;  
      
    listName = ancestorDiv.children[0];
    //console.log("name", listName.innerText);
    listNameEdit = ancestorDiv.children[1];
    //console.log("nameedit: ", listNameEdit.children[0].value);
    isDefault = ancestorDiv.children[2];
    //console.log('default', isDefault.children[1].value);
    isDefaultEdit = ancestorDiv.children[3];
    //console.log('defaulteidt', isDefaultEdit.children[1].value);

    if(clickedItem.name=="editPencil") {

        //define DOM elements
        saveButton=clickedItem.nextElementSibling.nextElementSibling;
        cancelButton=clickedItem.nextElementSibling.nextElementSibling.nextElementSibling;
   
        //reset edit field values
        listNameEdit.children[0].value=listName.innerText;
        isDefaultEdit.children[0].value=isDefault.children[1].value; 

        //hide original data fields / show edit fields 
    
        listName.hidden = true;
        listNameEdit.hidden = false;
        isDefault.hidden = true;
        isDefaultEdit.hidden = false;
        isDefaultEdit.style.zIndex="10000";
    
        //change button text and style
        clickedItem.hidden=true;
        clickedItem.nextElementSibling.hidden=true;
        saveButton.hidden=false;
        cancelButton.hidden=false;
        cancelButton.style="background-color:#f0f34a;color:#333";
        
        return;
    }

    if(clickedItem.name=="cancelButton"){
        //define DOM elements
        cancelButton=clickedItem;

        //reset edit fields values
        listNameEdit.children[0].value=listName.innerText;
        isDefaultEdit.children[1].value=isDefault.value;

        //show original data fields / hide edit fields       
        listName.hidden = false;
        listNameEdit.hidden = true;
        isDefault.hidden = false;
        isDefaultEdit.hidden = true;
        isDefaultEdit.style.zIndex="unset";
        isDefault.children[1].disabled = true;

        //change button text and style
        clickedItem.hidden=true;
        clickedItem.previousElementSibling.hidden=true;
        clickedItem.previousElementSibling.previousElementSibling.hidden=false;
        clickedItem.previousElementSibling.previousElementSibling.previousElementSibling.hidden=false;
    
        return;       
    }

    if(clickedItem.name=="saveButton"){
        console.log('imin Save');
        //define DOM elements
        cancelButton=clickedItem.nextElementSibling;
        
        //enable checkbox for php POST variable value check
        isDefault.children[1].disabled = false;

        //reset z-index
        isDefaultEdit.style.zIndex="unset";

        //change text and style of buttons
        clickedItem.hidden=true;
        clickedItem.nextElementSibling.hidden=true; 
        clickedItem.previousElementSibling.hidden=false;
        clickedItem.previousElementSibling.previousElementSibling.hidden=false;
          
        clickedItem.type="submit";  
    }     
}

function deleteList(clickedItem) {
    //Initialize variables
    var ancestorDiv;
    var isDefault;
    
    ancestorDiv = clickedItem.parentElement.parentElement;  
    isDefault = ancestorDiv.children[2];
    listName = clickedItem.parentElement.parentElement.children[0].innerText;
    
    //Check if list is default
    if (isDefault.children[1].checked == true) {
        alert("Can not delete default list. Please choose another default list or add a new default list before deleting.");
        return;
    }

    //Confirm delete with user
    if(!confirm(`Delete List "${listName}"?`)) {
        return;
    }

    //Submit form for PHP delete
    clickedItem.type="submit";
    return;   
}

//Remove frecency or category header after last item checked or unchecked
function removeHeader(formElement) {
    //Find previous element to item checked or unchecked
    isItItemHeaderElement=formElement.previousElementSibling;
    
    //check if this element is a header
    if (isItItemHeaderElement) {
        if (isItItemHeaderElement.classList.contains('list__container--items-itemHeader')) {
            //If element is a header, check if any children are displayed
            let nextItem = isItItemHeaderElement.nextElementSibling;
            while (nextItem) {
                //check if more items to check
                if (nextItem.children[0]) {
                    //if item not displayed, check next item
                    if(nextItem.children[0].style.display=="none") {
                        nextItem=nextItem.nextElementSibling;
                        continue;
                    }else{
                        //if item displayed, exit function without removing heading
                        return;
                    }
                //if no more items in grouping, exit checking if item is displayed
                }else{
                    break;
                }              
            }
            //if this line reached, means no displayed items in grouping, so remove header
            isItItemHeaderElement.innerText="";
        }else{
            //continue searching for header of this grouping
            removeHeader(isItItemHeaderElement);
        }
    }else{
        //top of list reached without finding a header class element, so exit function
        return;
    }
}

//customize view for premium customers
function premiumView(object) {
    const changeListButton = document.querySelector('#js--changeListButton');
    const changeListSelectorBox = document.querySelector('#js--selectList');
    changeListButton.style.display="none";
    changeListSelectorBox.classList.add("list__selectList--active");
}

/***********************
 * Mobile Navigation
 * ********************/
function mobileNav() {

    console.log('imin')
    const mainNav = document.querySelector("#js--mainNav");
    const mobileIcon = document.querySelector("#js--mobileNavIcon");
    const closeIcon = document.querySelector("#js--closeIcon");
    
    if (mobileIcon.classList.contains('displayBlock')) {
        console.log('if1');
        mobileIcon.classList.add('displayNone');
        mobileIcon.classList.remove('displayBlock');
        closeIcon.classList.add('displayBlock');
        closeIcon.classList.remove('displayNone');
        mainNav.classList.add('displayBlock');
        mainNav.classList.remove('displayNone');
        mainNav.classList.remove('flex');
    } else if (closeIcon.classList.contains('displayBlock')) {
        console.log('if2');
        mobileIcon.classList.remove('displayNone');
        mobileIcon.classList.add('displayBlock');
        closeIcon.classList.remove('displayBlock');      
        closeIcon.classList.add('displayNone');        
        mainNav.classList.add('displayNone');
        mainNav.classList.remove('displayBlock');
        mainNav.classList.remove('flex');
    }

    if (window.innerWidth>599) {
        console.log('if3');
        mobileIcon.classList.remove('displayBlock');
        mobileIcon.classList.add('displayNone');       
        closeIcon.classList.remove('displayBlock');  
        closeIcon.classList.add('displayNone');
        mainNav.classList.remove('displayBlock');
        mainNav.classList.remove('displayNone');
        mainNav.classList.add('flex');
    }
} 