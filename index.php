<?php include_once 'php/config/session.php'; ?>
<?php include_once 'php/config/config.php'; ?>
<?php include_once 'php/classes/Database.php'; ?>
<?php include_once 'php/reusables/queries.php'; ?>
<?php include_once 'php/reusables/helpers.php'; ?>
<?php //determine if login window needed
    //$_SESSION['id']="10";
    $loginNeeded = true;

    if (isset($_SESSION['id']) && $_SESSION['id'] !== '') {
        $loginNeeded = false;
    }
?>
<?php //on login submit button
    if(isset($_POST['submit'])){  
        $inputPassword = $_POST['password'];
        $inputEmail = $_POST['email'];
        
        try {
            $splQuery = "SELECT * FROM users WHERE email = :email";
            $statement = $db->prepare($splQuery);
            $statement->execute(array(':email'=>$inputEmail));

            if($row=$statement->fetch()){
                $id = $row['id'];
                $hashed_password = $row['password'];
                $password = $row['password'];
                $activated = $row['active'];
    
                if(password_verify($inputPassword, $hashed_password)){
                    if ($activated) {
                        //clear old session
                        if (isset($_SESSION['id'])) {
                            unset($_SESSION['listId']);
                            unset($_SESSION['orderBy']);
                            unset($_SESSION['viewBy']);
                            unset($_SESSION['id']);

                            // NOT YET IMPLEMENTED if(isset($_COOKIE['rememberUserCookie'])){
                            //     uset($_COOKIE['rememberUserCookie']);
                            //     setcookie('rememberUserCookie', null, -1, '/');
                            // } 
                        }
                        $_SESSION['id'] = $id;
                        $splQuery = "SELECT * FROM Lists WHERE listUserId = :id AND isDefault = 1";
                        $statement = $db->prepare($splQuery);
                        $statement->execute(array(':id'=>$id));
                        if($listRow=$statement->fetch()){
                            $_SESSION['listId'] = $listRow['listId'];                
                            header("Location: index.php");
                        } else {
                            //NOT YET IMPLEMENTED error handling
                            $result = "default list not found";
                        }
                    }else{
                        $result="Account not activated. Please check your email inbox for a verification email.";
                    }
                }else{           
                    $result = "Invalid password.<br>Please try again.";
                }
                    
            }else{
                $result = "Email not found.<br>Please try again.";
            }

        } catch (PDOException $ex) {
            $result = "An error occurred.<br>Error message number: ".$ex->getCode();
        }  
    }
?>
<?php //get list and categories
    
    if (isset($_POST['category'])) {
        $_SESSION['orderBy'] = 'category';
        $listItems=getList($db, $_SESSION['listId'], $frecencyInterval);
    } else if (isset($_POST['frecency'])) {
        $_SESSION['orderBy'] = 'frecency';
        $listItems=getList($db, $_SESSION['listId'], $frecencyInterval);
    } else if (isset($_POST['alpha'])) {
        $_SESSION['orderBy'] = 'alpha';
        $listItems=getList($db, $_SESSION['listId'], $frecencyInterval);
    } else {
        $listItems=getList($db, $_SESSION['listId'], $frecencyInterval);
    }
    //determine if filter by (un)checked
    if (isset($_POST['checked'])) {
        $_SESSION['viewBy'] = 'checked';
    } else if (isset($_POST['unChecked'])) {
        $_SESSION['viewBy'] = 'unChecked';
    } else if (isset($_POST['viewAll'])) {
        $_SESSION['viewBy'] = 'all';
    }
    //Get Categories Query dependency: php/reusables/queries.php
    $categories=getCategories($db);
?>
<?php //restore environment
    if (isset($_POST['addEditCancel'])) {
        unset($_POST['editItem']);
        unset($_POST['addEditCancel']);
    }
?>
<?php //add/edit submit button clicked
    if (isset($_POST['addEditSave'])) {
        //EDIT the item
        if (isset($_POST['id']) && $_POST['id'] !== '') {          
            //initialize data variables
            $id = '';
            $title = '';
            $category = '';
            $frecency = -1;
            $qty = 1;
            $firstClick = '';
            $lastClick = '';
            $numClicks = -1;
            $isChecked = 'off';

            //Get user input values from form
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
            }              
            if (isset($_POST['addTitle'])) {
                $title = $_POST['addTitle'];
            }              
            if (isset($_POST['addCategory'])) {
                $category = $_POST['addCategory'];
            } 
            if (isset($_POST['addFrecency'])) {
                $frecency = (int)$_POST['addFrecency'];
            } 
            if (isset($_POST['addQty'])) {
                $qty = (int)$_POST['addQty'];
            }               
            if (isset($_POST['addItemFirstClickEdit'])) {
                $firstClick = $_POST['addItemFirstClickEdit'];
            }               
            if (isset($_POST['addItemLastClickEdit'])) {
                $lastClick = $_POST['addItemLastClickEdit'];
            }               
            if (isset($_POST['checkBox'])) {
                $_POST['checkBox'] == 'on' ? $isChecked = 1 : $isChecked = 0;
            }
            //Calculate num of clicks
            $numClicks = calculateClicks($firstClick, $frecency, $frecencyInterval);
           
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
                                            WHERE id=:id";
                $statement = $db->prepare($query);
                $statement->execute(array(':title' => $title,
                                        ':category' => $category,
                                        ':qty' => $qty,
                                        ':firstClick' => $firstClick,
                                        ':lastClick' => $lastClick,
                                        ':numClicks' => $numClicks,
                                        ':isChecked' => $isChecked,
                                        ':id' => $id
                                    ));
            } catch (Exception $e) {
                //NOT YET IMPLEMENTED
            } 
            
        //ADD Item
        } else {
            
            //initialize data variables
            $id = time() . mt_rand() . 'tmurv'; //NOT YET IMPLEMENTED -- needs the userId to replace 'tmurv'
            $title = '';
            $category = '';
            $frecency = -1;
            $qty = 1;
            $isChecked = 'off';
            $numClicks = 0;
            $firstClick = '';
            $lastClick = '';

            //Get user input values from form
            if (isset($_POST['addTitle'])) {
                $title = $_POST['addTitle'];
            }              
            if (isset($_POST['addCategory'])) {
                $category = $_POST['addCategory'];
            } 
            if (isset($_POST['addFrecency'])) {
                $frecency = getFrecencyNumber($_POST['addFrecency']);
            } 
            if (isset($_POST['addQty'])) {
                $qty = (int)$_POST['addQty'];
            }        

            //if item checked, set initial click values
            if (isset($_POST['checkBox'])) {
                
                if ($_POST['checkBox'] == 'on') {
                    $isChecked = 1;
                    $firstClick = date('Y-m-d', strtotime("-100 days")); //Might be simulated for calculations NOT YET IMPLEMENTED, config number of days
                    $lastClick = date('Y-m-d H:i:s');
                    $frecencyIntervalsSinceFirstClick = (strtotime("now") - strtotime($firstClick))/86400;
                    $numClicks = calculateClicks($firstClick, $frecency, $frecencyInterval); //Might be simulated for calculations
                    
                } else {
                    $isChecked = 0;
                    $numClicks = 0;
                }
            } 
            //Create and execute the query         
            try {
                $query = "INSERT INTO ListItems (title, category, qty, id,  isChecked, numClicks, firstClick, lastClick, listId)
                                    VALUES (:title, :category, :qty, :id, :isChecked, :numClicks, :firstClick, :lastClick, :listId);";
                $statement = $db->prepare($query);
                $statement->execute(array(
                                    ':title'=>$title,
                                    ':category' => $category, 
                                    ':qty' => $qty, 
                                    ':id' => $id,                           
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
        
        //populate variables for edit form
        if (isset($_POST['editTitle'])) {
            $editTitle = $_POST['editTitle'];
        }                      
        if (isset($_POST['editQty'])) {
            $editQty = (int)$_POST['editQty'];
        }      
    }
?>
<?php //Delete item
    if (isset($_POST['itemDelete'])) {
        $id = $_POST['editId'];
        $query = "DELETE FROM ListItems WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(array(":id"=>$id));
        $listItems=getList($db, $frecencyInterval);
        header("Location: index.php", true, 301);
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
    <?php include 'php/reusables/mainnav.php'; ?>
    <section class="list">      
        <h1><?php echo (isset($_SESSION['id']) && $_SESSION['id'] !== '') ? $_SESSION['id'] : "My"; ?>'s 'Frecent' List</h1>
        <br> 
        <hr>
        <br>
        <!-- Login -->
        <div class="login signatureBox" style="display: <?php echo $loginNeeded ? 'block' : 'none' ?>">
            <div class="login__line1">
                <h3>Login<span> Page</span> </h3>
                <h4 class="login__line1--signup"><a href="signup.php">Easy Sign-up</a></h4>
                
            </div>
            <p style="color: tomato;"><?php echo isset($result) ? $result : ''; ?></p>
            <form action="index.php" method="post" class="login__form">
                                                
                <div class="login__form--email">
                    <label for="email">Email: </label>
                    <input name="email" type="email" value='tmurv@shaw.ca' required>                                           
                </div>
                            
                <div class="login__form--password">
                    <label for="password">Password: </label>
                    <input name="password" type="password" placeholder="password" value='password' required>                
                </div>
            
                <div class="login__form--submit">
                    <input type="submit" name="submit" class="btn" value="Submit"/>           
                    <h4 class="login__line1--signup"><a href="signup.php">Easy Sign-up<a></h4>
                </div>
            </form>
        </div>
        <!-- Search -->
        <div class="list__search" style="display: <?php echo $loginNeeded ? 'none' : 'block' ?>">
            <div class="list__search--input"><input type="text" class="list__search--input-input" placeholder="search list"><img src = "./img/searchIcon.png" class="list__search--input-icon" alt="Search Icon Magnifying glass"></div>
        </div>
        <!-- Add/Edit Item -->
        <div class="list__addItem" style="display: <?php echo $loginNeeded ? 'none' : 'block' ?>">
            <button class="btn btn__secondary" onClick="prepareEnvironmentAddItemForm();">Add Item</button>
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
                        <div class="list__container--items-itemCheckBox" checked>
                            <label for="checkBox">Check Item?</label>
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
                            >
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
                            <label for="addFrecency">'Frecency' (80+ often, 21-79 sometimes, 1-20 rarely, 0 one-time purchase)</label>   
                            <input name="addFrecency" type="text" value="<?php echo calculateFrecency($_SESSION['editItemObject']['numClicks'],$_SESSION['editItemObject']['firstClick'], $frecencyInterval); ?>"/>
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
                        <div class="" hidden><input id="js--addFrecencyEdit" type="text" name="addItemFrecency" value="<?php echo isset($_SESSION['editItemObject']) ? $_SESSION['editItemObject']['calcfrec'] : ''; ?>"></div>
                        <div class="" hidden><input id="js--addFirstClickEdit" type="text" name="addItemFirstClickEdit" value="<?php echo isset($_SESSION['editItemObject']) ? $_SESSION['editItemObject']['firstClick'] : ''; ?>"></div>
                        <div class="" hidden><input id="js--addLastClickEdit" type="text" name="addItemLastClickEdit" value="<?php echo isset($_SESSION['editItemObject']) ? $_SESSION['editItemObject']['lastClick'] : ''; ?>"></div>
                        <div class="" hidden><input id="js--addIdEdit" type="text" name="id" value="<?php echo isset($_SESSION['editItemObject']) ? $_SESSION['editItemObject']['id'] : ''; ?>"></div>
                    </div>
                    <div class="flex list__addItem--addItemForm-submitButtons">  
                        <input type="submit" class="btn btn__primary" name="addEditSave" id="js--saveAddEditItem" value="Save"/>
                        <input type="submit" class="btn btn__secondary" name="addEditCancel" id="js--cancelEditAddItem" onClick="restoreEnvironmentAddItemForm();" value="Cancel"/>
                    </div>    
                </form>  
            </div>
        </div>
        <!-- orderBy and viewBy -->
        <form action="index.php" method="post" name="viewByOrderBy">
            <div class="list__orderBy" style="display: <?php echo $loginNeeded ? 'none' : 'block' ?>" id="js--addItemOrderBy">
                <div class="list__orderBy--header">Order By:</div>
                <div class="list__orderBy--btns">                   
                        <input type="submit" class="btn btn__secondary btn__width125 <?php echo ($_SESSION['orderBy'] == 'frecency') ? 'btn__secondary--selected' : ''; ?>" name="frecency" value="Frecency"/>
                        <input type="submit" class="btn btn__secondary btn__width125 <?php echo ($_SESSION['orderBy'] == 'alpha') ? 'btn__secondary--selected' : ''; ?>" name="alpha" value="Alphabetical"/>
                        <input type="submit" class="btn btn__secondary btn__width125 <?php echo ($_SESSION['orderBy'] == 'category') ? 'btn__secondary--selected' : ''; ?>" name="category" value="Category"/>              
                </div>                
            </div>
            <div class="list__filterBy" style="display: <?php echo $loginNeeded ? 'none' : 'block' ?>" id="js--addItemFilterBy">
                <div class="list__filterBy--header">View By:</div>
                <div class="list__filterBy--btns">
                    <input type='submit' class="btn btn__secondary btn__width125 <?php echo ($_SESSION['viewBy'] == 'all') ? 'btn__secondary--selected' : ''; ?>" name="viewAll" value="All"/>
                    <input type='submit' class="btn btn__secondary btn__width125 <?php echo ($_SESSION['viewBy'] == 'checked') ? 'btn__secondary--selected' : ''; ?>" name="checked" value="Checked"/>
                    <input type='submit' class="btn btn__secondary btn__width125 <?php echo ($_SESSION['viewBy'] == 'unChecked') ? 'btn__secondary--selected' : ''; ?>" name="unChecked" value="Unchecked"/>               
                </div>
            </div>
        </form>
        <!-- List Container -->
        <div class="list__container" style="display: <?php echo ($loginNeeded | isset($_POST['edititem'])) ? 'none' : 'block' ?>" id="js--addItemListContainer">
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
                            //set is checkbox checked variable
                            $item['isChecked'] ? $checked = 'checked': $checked = '';
                        ?>
                        <form method='post'>               
                            <div class="list__container--items-item">
                                <div class="flex">
                                    <div class="list__container--items-itemQty"><input type="text" value="<?php echo $item['qty']; ?>" disabled></div>
                                    <div class="list__container--items-itemQtyPreEdit" hidden><input type="text" name="editQty" value="<?php echo $item['qty']; ?>"></div>
                                    <div class="list__container--items-itemTitle" id='js--tempId'><input type="text" value="<?php echo $item['title']; ?>"></div>            
                                    <div class="list__container--items-itemTitlePreEdit" hidden><input type="text" name="editTitle" value="<?php echo $item['title']; ?>"></div>            
                                    <div class="list__container--items-itemCheckBox"><input type="checkbox" name="checkBox" data-id="<?php echo $item['id']; ?>" onclick="updateNumClicks(this.dataset.id, this.checked);" <?php echo $checked ?>></div>                                  
                                    <button type='submit' class="list__container--items-itemEdit js--editItem"  name="editItem"><img src="./img/editItemIcon.png" alt="Pencil icon for edit list item"></button>                                  
                                    <button type='submit' class="list__container--items-itemDelete" name='itemDelete'><img src="./img/deleteRedX.png" name="deleteItem" alt="Big red X icon for delete list item"></button>  
                                    <div class="list__container--items-frecency" hidden><input type="text" name='frecency' value="<?php echo $item['calcfrec']; ?>"></div>                                                   
                                    <div class="list__container--items-itemId" hidden><input type="text" name='editId' value="<?php echo $item['id']; ?>"></div>                                                   
                                </div>    
                            </div>
                        </form>    
                    <?php endforeach; ?> 
                </div>                            
            </div>                
        </div>        
    </section>
    <!-- update number of clicks API -->
    <script>
        function updateNumClicks(str, isChecked) {
            console.log(str + '|' + isChecked);
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function (){               
                //NOT YET implemented Message to user box on erro
                // if (this.readyState == 4 && this.status == 200) {
                //     //document.getElementById("js--tempId").style.background-color = 'green';
                //     document.getElementById("js--tempId").innerHTML = this.responseText;
                // } else {
                //     //document.getElementById("js--tempId").style.background-color = 'red';
                //     //document.getElementById("js--tempId").innerHTML = this.responseText;
                // }
            };
            xmlhttp.open("GET", "php/reusables/updateNumClicksAPI.php?id=" + str + "&ischecked=" + isChecked, true);
            xmlhttp.send();         
        }   
    </script>
</body>
</html>