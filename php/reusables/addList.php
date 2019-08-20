<!-- Add List -->
<form action="addEditLists.php" method="post">
    <div class="flex">
        <div class="addEditLists__addArea--name">
            <label for="email">List Name: </label>
            <input name="addListName" type="text" required>                                           
        </div>
        <div class="addEditLists__addArea--default">
            <label for="default">Set as default? </label>
            <input name="isDefault" type="checkbox">             
        </div>
    </div>
    <div class="addEditLists__addArea--submit">                             
        <button 
            name="addListSubmit" 
            type="submit" 
            class="btn btn__secondary"
        >
            Add List
        </button>
        <button 
            name="addListCancel" 
            type="reset" 
            class="btn btn__primaryVeryDark" 
            id="addListCancel"
        >
            Reset
        </button>                 
    </div>
</form>