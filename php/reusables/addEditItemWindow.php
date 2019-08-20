<!-- Add/Edit Item Window -->
<div 
            class="list__addItem" 
            style="display: 
                <?php 
                    echo $loginNeeded 
                    ? 'none' 
                    : 'block' 
                ?>
            ">
            <button 
                class="btn btn__secondary" 
                id="js--addItemButton" 
                style="display: 
                    <?php 
                        echo (isset($_POST['editItem'])) 
                        ? 'none' 
                        : 'block' 
                    ?>
                " 
                onClick="prepareEnvironmentAddItemForm();"
            >
                Add Item
            </button>
            <div class="list__addItem--addItemForm" style="display: <?php echo (isset($_POST['editItem'])) ? 'block' : 'none' ?>" id="js--addItemForm">                 
                <!-- Add/Edit Item Form Headers -->
                <div>
                    <h3>Add/Edit List Item</h3>                       
                </div>
                <hr>  
                <!-- Add/Edit Item Form  -->
                <form name="addItem" action="" method="post">                               
                    <div class="flexWrap">
                        <div class="list__addItem--addItemForm-qty"><label for="addQty">Qty</label><input name="addQty" type="text" value="<?php echo (isset($_POST['editItem'])) ? $_POST['editQty'] : 1 ?>"></div>
                        <div class="list__addItem--addItemForm-checkBox" checked>
                            <!-- <label for="checkBox">Check Item?</label> -->
                            <p>Check Item?</p>
                            <input type="checkbox" name="checkBox" 
                                <?php 
                                    if (isset($_POST['editItem'])) {
                                        if (isset($_POST['checkBox'])) {
                                            echo ($_POST['checkBox'] == 'on') ?  'checked' : ''; 
                                        }
                                    } else { 
                                        echo 'checked'; 
                                    } 
                                ?>
                            />
                        </div>
                        <div class="list__addItem--addItemForm-title"><label for="addTitle">Item Name</label><input name="addTitle" type="text" value="<?php echo (isset($_POST['editItem'])) ? $_POST['editTitle'] : '' ?>"></div>
                        <!-- <div class="showAddItemCategory"><label for="addCategory">Category</label><input name="addCategory" type="text" id="js--addCategory" placeholder="produce dairy etc"></div> -->
                        <div class="list__addItem--addItemForm-category">
                            <label for="addCategory">Category</label>
                            <select name="addCategory">
                                <?php if ($_SESSION['userInfo']['premium']) : ?>
                                    <?php foreach($categories as $category) : ?> 
                                        <?php if (isset($_POST['editItem'])) : ?>
                                            <?php $selected = ""; ?> 
                                            <?php if(strtolower($category)==strtolower($_SESSION['editItemObject']['category'])) {$selected = 'selected';} ?>
                                            <option value="<?php echo $category; ?>" <?php echo $selected; ?>>
                                                <?php echo $category; ?>
                                            </option>
                                        <?php else : ?>                                       
                                            <option value="<?php echo $category; ?>">
                                                <?php echo $category; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                <?php else : ?>
                                    
                                    <?php foreach($categories as $row) : ?> 
                                        <?php if (isset($_POST['editItem'])) : ?>
                                            <?php $selected = ""; ?> 
                                            <?php if(strtolower($row['category'])==strtolower($_SESSION['editItemObject']['category'])) {$selected = 'selected';} ?>
                                            <option value="<?php echo $row['category']; ?>" <?php echo $selected; ?>>
                                                <?php echo $row['category']; ?>
                                            </option>
                                        <?php else : ?>                                       
                                            <option value="<?php echo $row['category']; ?>">
                                                <?php echo $row['category']; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                            </select>                                   
                        </div>
                        <div class="list__addItem--addItemForm-frecency">
                        <?php if (isset($_POST['editItem'])) : ?>
                            <label for="addFrecency">'Frecency' (1-100*):</label>   
                            <input name="addFrecency" type="text" value="<?php echo $editFrecency; ?>"/>
                        <?php else : ?>
                            <label for="addFrecency">Starting 'Frecency'</label>
                            <select name="addFrecency" id="js--addFrecencyRating">
                                <option value="often">Often</option>
                                <option value="sometimes" selected>Sometimes</option>
                                <option value="rarely">Rarely</option>
                                <option value="oneTimePurchase">One-time Purchase</option>
                            </select>
                        <?php endif; ?>

                        </div>
                        <div class="" hidden><input id="js--addFrecencyEdit" type="text" name="addItemFrecency" value="<?php echo isset($_SESSION['editItemObject']) ? calculateFrecency($_SESSION['editItemObject']['numClicks'], $_SESSION['editItemObject']['firstClick'], $frecencyInterval) : ''; ?>"></div>
                        <div class="" hidden><input id="js--addFirstClickEdit" type="text" name="addItemFirstClickEdit" value="<?php echo isset($_SESSION['editItemObject']) ? $_SESSION['editItemObject']['firstClick'] : ''; ?>"></div>
                        <div class="" hidden><input id="js--addLastClickEdit" type="text" name="addItemLastClickEdit" value="<?php echo isset($_SESSION['editItemObject']) ? $_SESSION['editItemObject']['lastClick'] : ''; ?>"></div>
                        <div class="" hidden><input id="js--addIdEdit" type="text" name="listItemId" value="<?php echo isset($_SESSION['editItemObject']) ? $_SESSION['editItemObject']['listItemId'] : ''; ?>"></div>
                    </div>
                    <div class="flex list__addItem--addItemForm-submitButtons">  
                        <input type="submit" class="btn btn__secondary" name="addEditSave" id="js--saveAddEditItem" value="Save"/>
                        <input type="submit" class="btn btn__primary" name="addEditCancel" id="js--cancelEditAddItem" onClick="restoreEnvironmentAddItemForm();" value="Cancel"/>
                    </div>
                    <br>
                    <p style="font-size: 14px;">* 80+ often, 21-79 sometimes, 1-20 rarely, 0 one-time purchase</p>
                </form>  
            </div>
        </div>