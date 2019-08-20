<!-- edit lists -->
<?php foreach($lists as $list) : ?>
    <form method="post" name="edit" action="addEditLists.php">                  
        <div class="addEditLists__list--lineItem">
            <?php 
                if($list['isDefault']==1) {
                    $checked = 'checked';
                }else{
                    $checked="";
                } 
            ?>
            <div class="addEditLists__list--lineItem-listName" id="js--listName">
                <p> 
                    <?php echo $list['listName']; ?>
                    &nbsp;
                    <span><?php echo $checked ? '(default)':''; ?></span>
                </p>
            </div>
            <div class="addEditLists__list--lineItem-listName" hidden>
                <input 
                    class="addEditLists__list--lineItem-listName" 
                    name="listName" 
                    id="js--listNameEdit" 
                    type="text" 
                    value="<?php echo $list['listName']; ?>"
                />                            
            </div>
            <div class="addEditLists__list--lineItem-isDefault">
                <input 
                    class="addEditLists__list--lineItem-isDefault" 
                    id="js--isDefault" 
                    name="isDefault" 
                    type="checkbox" <?php echo $checked ?> 
                    hidden
                />
            </div>
            <div class="addEdit___list--lineItem-isDefault" hidden>
                <label for="isDefault">Default?</label>
                <input 
                    class="addEditLists__list--lineItem-isDefault" 
                    id="js--isDefaultEdit" 
                    name="isDefault" 
                    type="checkbox" <?php echo $checked ?> 
                />
            </div>
            <div class="addEditLists__list--lineItem-listId" hidden>
                <input 
                    class="list__lineItem--listId" 
                    name="listId" 
                    type="text" 
                    value="<?php echo $list['listId']; ?>" 
                    hidden
                />
            </div>
            <div class="addEditLists__list--lineItem-editDelete">
                <button 
                    type='button' 
                    class="addEditLists__list--lineItem-editDeletePencil" 
                    name="editPencil" 
                    onClick="startEditLists(this)"
                >
                    <img src="./img/editItemIcon.png" alt="Pencil icon for edit list item">
                </button>                                                                                                
                <button 
                    type='button' 
                    class="addEditLists__list--lineItem-editDeleteX" 
                    name='delete' 
                    onClick="deleteList(this);"
                >
                    <img src="./img/deleteRedX.png" name="deleteItem" alt="Big red X icon for delete list item">
                </button>
                <button 
                    name="saveButton" 
                    type="button" 
                    class="btn btn__secondary" 
                    onClick="startEditLists(this)" 
                    hidden
                >
                    Save
                </button>
                <button 
                    name="cancelButton" 
                    type="button" 
                    class="btn btn__primaryVeryDark" 
                    onClick="startEditLists(this)" 
                    hidden
                >
                    Cancel
                </button>
            </div>
        </div>
    </form>
<?php endforeach; ?>