<!-- display list item -->
<form method='post'>               
    <div class="list__container--items-item">
        <div class="flex">
            <div class="flex__line1">
                <input 
                    type="checkbox" 
                    name="checkBox" 
                    data-itemid="<?php echo $item['listItemId']; ?>" 
                    data-name="name" 
                    onclick="updateNumClicks(this.dataset.itemid, this.checked);" 
                    <?php echo $item['isChecked'] ? 'checked' : ''; ?>
                />                                                 
                <div class="list__container--items-itemQty">
                    <input 
                        type="text" 
                        value="<?php echo $item['qty']; ?>" 
                        disabled
                    />
                </div>
                <div class="list__container--items-itemQtyPreEdit" hidden>
                    <input 
                        type="text" 
                        name="editQty" 
                        value="<?php echo $item['qty']; ?>"
                    />
                </div>
                <div class="list__container--items-itemTitle" id='js--tempId'>
                    <input 
                        type="text" 
                        value="<?php echo $item['title']; ?>" 
                        disabled
                    />
                </div>            
                <div class="list__container--items-itemTitlePreEdit" hidden>
                    <input 
                        type="text" 
                        name="editTitle" 
                        value="<?php echo $item['title']; ?>"
                    />
                </div>            
                <button 
                    type='submit' 
                    class="list__container--items-itemEdit js--editItem" 
                    name="editItem"
                >
                    <img src="./img/editItemIcon.png" alt="Pencil icon for edit list item"/>
                </button>                                                                                                     
                <button 
                    type='button' 
                    class="list__container--items-itemDelete" 
                    onClick="verifyDeleteItem(this);" 
                    name='itemDelete'
                >
                    <img 
                        src="./img/deleteRedX.png" 
                        name="deleteItem" 
                        alt="Big red X icon for delete list item"
                    >
                </button>
                <input name='scrollPosition' hidden>
                <div class="list__container--items-frecency" hidden>
                    <input 
                        type="text" 
                        name='frecency' 
                        value="<?php echo $item['calcfrec']; ?>"
                    />
                </div>                                                   
                <div class="list__container--items-itemId" hidden>
                    <input 
                        type="text" 
                        name='editId' 
                        value="<?php echo $item['listItemId']; ?>"
                    />
                </div>
            </div>                                                   -->
        </div>    
    </div>
</form>