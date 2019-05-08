<?php include_once 'php/config/session.php'; ?>
<?php include_once 'php/config/config.php'; ?>
<?php include_once 'php/classes/Database.php'; ?>
<?php include_once 'php/reusables/queries.php'; ?>
<?php include_once 'php/reusables/helpers.php'; ?>
<?php //redirect to login if needed
    $loginNeeded = true;
    if (isset($_SESSION['userId']) && $_SESSION['userId'] !== '') {
        $loginNeeded = false;
    } else {
        header("Location: login.php");  
        exit();
    }
?>
<?php //change list submit clicked
    if (isset($_POST['selectList'])) {
        $_SESSION['listId'] = (int)$_POST['selectList'];
        unset($_POST['selectList']);
    }
?>
<?php //get userInfo, lists and categories
    
    if (!isset($_SESSION['orderBy']) || $_SESSION['orderBy'] == '') $_SESSION['orderBy'] = 'alpha';
    if (isset($_POST['category'])) {
        $_SESSION['orderBy'] = 'category';
        unset($_POST['category']);
        $listItems=getList($db, $_SESSION['listId'], $frecencyInterval);
    } else if (isset($_POST['frecencyOrder'])) {
        $_SESSION['orderBy'] = 'frecency';      
        unset($_POST['frecencyOrder']);
        $listItems=getList($db, $_SESSION['listId'], $frecencyInterval);
    } else if (isset($_POST['alpha'])) {
        $_SESSION['orderBy'] = 'alpha';
        unset($_POST['alpha']);
        $listItems=getList($db, $_SESSION['listId'], $frecencyInterval);
    } else {
        $listItems=getList($db, $_SESSION['listId'], $frecencyInterval);
    }
    //determine if filter by (un)checked
    if (isset($_POST['checked'])) {
        $_SESSION['viewBy'] = 'checked';
        unset($_POST['checked']);
    } else if (isset($_POST['unChecked'])) {
        $_SESSION['viewBy'] = 'unChecked';
        unset($_POST['unChecked']);
    } else if (isset($_POST['viewAll'])) {
        $_SESSION['viewBy'] = 'all';
        unset($_POST['viewAll']);
    }
    //Get Categories - Query dependency: php/reusables/queries.php
    $categories=getCategories($db);
    
    //Get List Info for List Name - Query dependency: php/reusables/queries.php
    $listInfo=getListInfo($db);

    //Get all lists from user - Query dependency: php/reusables/queries.php
    $userLists=getAllUserLists($db);
?>
<?php //restore environment
    if (isset($_POST['addEditCancel'])) {
        unset($_POST['editItem']);
        unset($_POST['addEditCancel']);
    }        
    
    if (isset($_POST['scrollPosition'])) {
        $_SESSION['scrollPosition'] = $_POST['scrollPosition'];       
        unset($_POST['scrollPosition']);
        
    }
?>
<?php //add/edit submit button clicked

    if (isset($_POST['addEditSave'])) {
        
        //EDIT the item
        if (isset($_POST['listItemId']) && $_POST['listItemId'] !== '') {          
            //initialize data variables
            $listItemId = '';
            $title = '';
            $category = '';
            $frecency = -1;
            $qty = 1;
            $firstClick = '';
            $lastClick = '';
            $numClicks = -1;
            $isChecked = 'off';

            //sanitize and assign user input values           
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['addTitle'])) {
                    $title = testInput($_POST["addTitle"]);
                }
                if (isset($_POST['addCategory'])) {
                    $category = testInput($_POST["addCategory"]);
                }
                if (isset($_POST['addFrecency'])) {
                    $frecency = testInput($_POST["addFrecency"]);
                }
                if (isset($_POST["addQty"])) {
                    $qty = testInput($_POST["addQty"]);
                }
                if (isset($_POST["checkBox"])) {
                    $checkBox = testInput($_POST["checkBox"]);
                    ($checkBox == 'on') ? $isChecked = 1 : $isChecked = 0;
                }               
            }

            //assign values from hidden fields not touch by user
            if (isset($_POST['listItemId'])) {
                $listItemId = $_POST['listItemId'];
            }                                        
            if (isset($_POST['addItemFirstClickEdit'])) {
                $firstClick = $_POST['addItemFirstClickEdit'];
            }               
            if (isset($_POST['addItemLastClickEdit'])) {
                $lastClick = $_POST['addItemLastClickEdit'];
            }               
            
            //Calculate num of clicks
            $numClicks = calculateClicks($firstClick, $lastClick, $frecency, $frecencyInterval);
           
            //edit the item in db
            try {
                //Create and execute query
                $query = "UPDATE ListItems SET title=:title, 
                                                category=:category, 
                                                qty=:qty,
                                                firstClick=:firstClick,
                                                lastClick=:lastClick,
                                                numClicks=:numClicks,
                                                isChecked=:isChecked
                                            WHERE listItemId=:listItemId";
                $statement = $db->prepare($query);
                $statement->execute(array(':title' => $title,
                                        ':category' => $category,
                                        ':qty' => $qty,
                                        ':firstClick' => $firstClick,
                                        ':lastClick' => $lastClick,
                                        ':numClicks' => $numClicks,
                                        ':isChecked' => $isChecked,
                                        ':listItemId' => $listItemId
                                    ));
            } catch (Exception $e) {
                //NOT YET IMPLEMENTED
            } 
            
        //ADD Item
        } else {    
            //initialize data variables
            $listItemId = '';
            $title = '';
            $category = '';
            $frecencyWord = '';
            $qty = 1;
            $firstClick = '';
            $lastClick = '';
            $numClicks = -1;
            $isChecked = 'off';

            //create new list item id
            $listItemId = time().mt_rand().$_SESSION['userId'];

            //sanitize and assign user input values           
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['addTitle'])) {
                    $title = testInput($_POST["addTitle"]);
                }
                if (isset($_POST['addCategory'])) {
                    $category = testInput($_POST["addCategory"]);
                }
                if (isset($_POST['addFrecency'])) {
                    $frecencyWord = testInput($_POST["addFrecency"]);
                }
                if (isset($_POST["addQty"])) {
                    $qty = testInput($_POST["addQty"]);
                }
                if (isset($_POST["checkBox"])) {
                    $checkBox = testInput($_POST["checkBox"]);
                    ($checkBox == 'on') ? $isChecked = 1 : $isChecked = 0;
                }
            } 
            
            //determine faux first click and last click based on user entered frecency
            $firstClick = date("Y-m-d H:i:s", time() - ($frecencyInterval*$minimumIntervals)); //Might be simulated for calculations NOT YET IMPLEMENTED, config number of days
            $lastClick = date('Y-m-d H:i:s');

            //calculate number of clicks to match user frecency word input
            $frecencyNumber = getFrecencyNumber($frecencyWord);
            $numClicks = calculateClicks($firstClick, $lastClick, $frecencyNumber, $frecencyInterval); //Might be simulated for calculations
            
            //Create and execute the query         
            try {
                $query = "INSERT INTO ListItems (title, category, qty, listItemId,  isChecked, numClicks, firstClick, lastClick, listId)
                                    VALUES (:title, :category, :qty, :listItemId, :isChecked, :numClicks, :firstClick, :lastClick, :listId);";
                $statement = $db->prepare($query);
                $statement->execute(array(
                                    ':title'=>$title,
                                    ':category' => $category, 
                                    ':qty' => $qty, 
                                    ':listItemId' => $listItemId,                           
                                    ':isChecked' => $isChecked,
                                    ':numClicks' => $numClicks,
                                    ':firstClick' => $firstClick,
                                    ':lastClick' => $lastClick,
                                    ':listId' => $_SESSION['listId']
                ));
            } catch (Exception $e) {
                //NOT YET IMPLEMENTED
            }           
        }
        //restore environment
        $_POST = [];
        header("Location: index.php", true, 301);
        exit();   
    }
?>
<?php //show edit item window
    if (isset($_POST['editItem'])) {
        $editId = '';
        $editFrecency = -1;
        $editQty = 1;
       
        //Get id from form
        if (isset($_POST['editId'])) {
            $editId = $_POST['editId'];
        } 
        //Get editItem values from db that are not in post variable
        $_SESSION['editItemObject'] = getListItemById($db, $editId, $frecencyInterval);
        $editFrecency = calculateFrecency($_SESSION['editItemObject']['numClicks'], $_SESSION['editItemObject']['firstClick'], $frecencyInterval);
        
        //populate variables for edit form
        if (isset($_POST['editTitle'])) {
            $editTitle = $_POST['editTitle'];
        }                      
        if (isset($_POST['editQty'])) {
            $editQty = (int)$_POST['editQty'];
        }            
        if (isset($_POST['editQty'])) {
            $editQty = (int)$_POST['editQty'];
        }            
    }
?>
<?php //Delete item

    if (isset($_POST['itemDelete'])) {
        if (isset($_POST['editId'])) $listItemId = $_POST['editId'];
        $query = "DELETE FROM ListItems WHERE listItemId = :listItemId";
        $statement = $db->prepare($query);
        $statement->execute(array(":listItemId"=>$listItemId));
        //$listItems=getList($db, $listId, $frecencyInterval);
        header("Location: index.php", true, 301);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
<div class="outer">
    <!-- store scroll position variable for repositioning after edit/delete -->
    <div id="js--scrollPositionDiv" hidden><?php if (isset($_SESSION['scrollPosition'])) {echo $_SESSION['scrollPosition']; $_SESSION['scrollPosition'] = '0';} ?></div>
    <!-- display main navigation -->
    <?php include 'php/reusables/mainnav.php'; ?>
    <!-- add/edit list, and main list section -->
    <section class="list">
        <h1><?php echo (isset($listInfo['listName'])) ? $listInfo['listName'] : "My 'Frecent' List"; ?></h1>
        <?php if (isset($_SESSION['userInfo']['premium']) && $_SESSION['userInfo']['premium']) {echo "<div id='js--changeListButton' class='list__selectList--changeListButton' onClick='premiumView()'>Change List</div>";} ?>
        <!-- if change list selected, display list choices -->
        <form action="index.php" name="changeListSelectBox" class='list__selectList' id="js--selectList" method='post'>
            <select name="selectList" onChange="this.classList.remove('list__selectList--active'); document.querySelector('#js--changeListButton').style.display='inline-block'; console.log('selected'); this.form.submit();">
                <?php foreach($userLists as $row) : ?>                     
                    <?php $selected = ""; ?> 
                    <?php if(strtolower($row['listId'])==strtolower($_SESSION['listId'])) {$selected = 'selected';} ?>
                    <option value="<?php echo $row['listId']; ?>" <?php echo $selected; ?>>
                        <?php echo $row['listName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Submit" hidden>
        </form>      
        <br>
        <hr>
        <br>
        <!-- Add/Edit Item Window -->
        <div class="list__addItem" style="display: <?php echo $loginNeeded ? 'none' : 'block' ?>">
            <button class="btn btn__secondary" id="js--addItemButton" style="display: <?php echo (isset($_POST['editItem'])) ? 'none' : 'block' ?>" onClick="prepareEnvironmentAddItemForm();">Add Item</button>
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
        <!-- orderBy and viewBy buttons-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="viewByOrderBy">
            <div class="list__orderBy" style="display: <?php echo $loginNeeded || isset($_POST['editItem']) ? 'none' : 'block' ?>" id="js--addItemOrderBy">
                <div class="list__orderBy--header">Order By:</div>
                <div class="list__orderBy--btns">     
                    <input type="submit" name="frecencyOrder" class="btn btn__secondary btn__width125 <?php echo ($_SESSION['orderBy'] == 'frecency') ? 'btn__secondary--selected' : ''; ?>" value="Frecency"/>                         
                    <input type="submit" name="alpha" class="btn btn__secondary btn__width125 <?php echo ($_SESSION['orderBy'] == 'alpha') ? 'btn__secondary--selected' : ''; ?>" value="Alphabetical"/>
                    <input type="submit" name="category" class="btn btn__secondary btn__width125 <?php echo ($_SESSION['orderBy'] == 'category') ? 'btn__secondary--selected' : ''; ?>" value="Category"/>      
                </div>                
            </div>
            <div class="list__filterBy" style="display: <?php echo $loginNeeded || isset($_POST['editItem'])? 'none' : 'block' ?>" id="js--addItemFilterBy">
                <div class="list__filterBy--header">View By:</div>
                <div class="list__filterBy--btns">
                    <input type='submit' class="btn btn__secondary btn__width125 <?php echo ($_SESSION['viewBy'] == 'all') ? 'btn__secondary--selected' : ''; ?>" name="viewAll" value="All"/>
                    <input type='submit' id='js--filterByChecked' class="btn btn__secondary btn__width125 <?php echo ($_SESSION['viewBy'] == 'checked') ? 'btn__secondary--selected' : ''; ?>" name="checked" value="Checked"/>
                    <input type='submit' id='js--filterByUnchecked' class="btn btn__secondary btn__width125 <?php echo ($_SESSION['viewBy'] == 'unChecked') ? 'btn__secondary--selected' : ''; ?>" name="unChecked" value="Unchecked"/>               
                </div>
            </div>
        </form>
        <!-- List Container -->
        <div class="list__container" style="display: <?php echo ($loginNeeded || isset($_POST['editItem'])) ? 'none' : 'block' ?>" id="js--addItemListContainer">
            <div class="list__container--headers">
                <p>Qty</p>
                <p>Item</p>
                <p>Checked</p>
            </div>           
            <div class="list__container--items">
                <div class="list__container--items-item">                   
                    <?php $displayHeader = ''; ?>
                    <?php foreach($listItems as $item) : ?>
                        <?php
                            //prepare list item environment and variables
                            //filter by viewChecked
                            if ($_SESSION['viewBy'] == 'checked' && $item['isChecked'] == false) continue;
                            if ($_SESSION['viewBy'] == 'unChecked' && $item['isChecked'] == true) continue;
                                
                            //prepare heading variable if needed                          
                            if ($_SESSION['orderBy']=='category') {
                                if ($displayHeader !== $item['category']) {
                                    echo '<div class="list__container--items-itemHeader">'.$item['category'].'</div>';
                                    $displayHeader = $item['category'];
                                }
                            } else if ($_SESSION['orderBy']=='frecency') {
                                $frecencyWord = getFrecencyWord(calculateFrecency($item['numClicks'], $item['firstClick'], $frecencyInterval));
                                
                                if ($displayHeader !== $frecencyWord) {
                                    echo '<div class="list__container--items-itemHeader">'.$frecencyWord.'</div>';
                                    $displayHeader = $frecencyWord;
                                }
                            }                                                 
                        ?>
                        <!-- display list item -->
                        <form method='post'>               
                            <div class="list__container--items-item">
                                <div class="flex">
                                    <div class="flex__line1">
                                        <div class="list__container--items-itemQty"><input type="text" value="<?php echo $item['qty']; ?>" disabled></div>
                                        <div class="list__container--items-itemQtyPreEdit" hidden><input type="text" name="editQty" value="<?php echo $item['qty']; ?>"></div>
                                        <div class="list__container--items-itemTitle" id='js--tempId'><input type="text" value="<?php echo $item['title']; ?>" disabled></div>            
                                        <div class="list__container--items-itemTitlePreEdit" hidden><input type="text" name="editTitle" value="<?php echo $item['title']; ?>"></div>            
                                    </div>
                                    <div class="flex__line2">
                                        <input type="checkbox" name="checkBox" data-itemid="<?php echo $item['listItemId']; ?>" data-name="name" onclick="updateNumClicks(this.dataset.itemid, this.checked);" <?php echo $item['isChecked'] ? 'checked' : ''; ?>>                                  
                                        <button type='submit' class="list__container--items-itemEdit js--editItem" name="editItem"><img src="./img/editItemIcon.png" alt="Pencil icon for edit list item"></button>                                                                                                     
                                        <button type='button' class="list__container--items-itemDelete" onClick="verifyDeleteItem(this);" name='itemDelete'><img src="./img/deleteRedX.png" name="deleteItem" alt="Big red X icon for delete list item"></button>
                                        <input name='scrollPosition' hidden>
                                        <div class="list__container--items-frecency" hidden><input type="text" name='frecency' value="<?php echo $item['calcfrec']; ?>"></div>                                                   
                                        <div class="list__container--items-itemId" hidden><input type="text" name='editId' value="<?php echo $item['listItemId']; ?>"></div>
                                    </div>                                                   
                                </div>    
                            </div>
                        </form>    
                    <?php endforeach; ?> 
                </div>                            
            </div>                
        </div>        
    </section>
</div>
    <!-- update number of clicks API -->
    <script>
        function updateNumClicks(listItemId, isChecked) {
            //intialize variables
            const checkedElement = document.querySelector(`[data-itemid="${listItemId}"]`);
            const filterByChecked = document.querySelector('#js--filterByChecked');
            const filterByUnchecked = document.querySelector('#js--filterByUnchecked');

            // remove item from list if checked/unchecked does not matched list state
            if (filterByChecked.classList.contains('btn__secondary--selected')) {
                isChecked ? '' : checkedElement.parentElement.parentElement.parentElement.style.display = 'none';            
            } else if (filterByUnchecked.classList.contains('btn__secondary--selected')) {
                !isChecked ? '' : checkedElement.parentElement.parentElement.parentElement.style.display = 'none';                       
            }

            //if last element, in displayed grouping in list, remove header from list
            removeHeader(checkedElement.parentElement.parentElement.parentElement.parentElement);
            
            //isChecked ? '' : console.log('isChecked false'); 
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function (){               
                //NOT YET implemented Message to user box on error
                // if (this.readyState == 4 && this.status == 200) {
                //     //document.getElementById("js--tempId").style.background-color = 'green';
                //     document.getElementById("js--tempId").innerHTML = this.responseText;
                // } else {
                //     //document.getElementById("js--tempId").style.background-color = 'red';
                //     //document.getElementById("js--tempId").innerHTML = this.responseText;
                // }
            };
            xmlhttp.open("GET", "php/reusables/updateNumClicksAPI.php?listItemId=" + listItemId + "&ischecked=" + isChecked, true);
            xmlhttp.send();       
        }   
    </script>
</body>
</html>