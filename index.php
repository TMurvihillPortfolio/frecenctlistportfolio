<?php include_once 'php/config/session.php'; ?>
<?php include_once 'php/config/config.php'; ?>
<?php include_once 'php/classes/Database.php'; ?>
<?php include_once 'php/reusables/queries.php'; ?>
<?php include_once 'php/reusables/helpers.php'; ?>
<?php 
    //determine the list order
    if (isset($_POST['category'])) {
        $_SESSION['orderBy'] = 'category';
        $listItems=getList($db);
    } else if (isset($_POST['frecency'])) {
        $_SESSION['orderBy'] = 'frecency';
        $listItems=getList($db);
    } else if (isset($_POST['alpha'])) {
        $_SESSION['orderBy'] = 'alpha';
        $listItems=getList($db);
    } else {
        $listItems=getList($db);
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
<?php 
    if (isset($_POST['addEditCancel'])) {
        unset($_POST['editItem']);
        unset($_POST['addEditCancel']);
    }
?>
<?php
    if (isset($_POST['addEditSave'])) {
        //EDIT the item
        if (isset($_POST['id']) && $_POST['id'] !== '') {          
            //initialize data variables
            $id = '';
            $title = '';
            $category = '';
            $frecency = -1;
            $qty = 1;
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
                //NOT YET IMPLEMENTED -- update num of clicks based on frecency number
            } 
            if (isset($_POST['addQty'])) {
                $qty = (int)$_POST['addQty'];
            }        
            //if item checked, set click values
            if (isset($_POST['checkBox'])) {
                $_POST['checkBox'] == 'on' ? $isChecked = 1 : $isChecked = 0;
            }
            
            //edit the item in db
            try {
                //Create and execute query
                $query = "UPDATE  ListItems SET title=:title, 
                                                category=:category, 
                                                frecency=:frecency, 
                                                qty=:qty, 
                                                isChecked=:isChecked
                                            WHERE id=:id";
                $statement = $db->prepare($query);
                $statement->execute(array(':title' => $title,
                                        ':category' => $category,
                                        ':frecency' => $frecency,
                                        ':qty' => $qty,
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
                    $firstClick = date('Y-m-d', strtotime("-90 days")); //Might be simulated for calculations NOT YET IMPLEMENTED, config number of days
                    $lastClick = date('Y-m-d H:i:s');
                    echo $firstClick.$lastClick;
                    exit();
                    $numClicks = calculateClicks($db, $firstClick, $frecency); //Might be simulated for calculations
                    
                } else {
                    $isChecked = 0;
                    $numClicks = 0;
                }
            } 
            //Create and execute the query         
            try {
                $query = "INSERT INTO ListItems (title, category, frecency, qty, id,  isChecked, numClicks, firstClick, lastClick)
                                    VALUES (:title, :category, :frecency, :qty, :id, :isChecked, :numClicks, :firstClick, :lastClick);";
                $statement = $db->prepare($query);
                $statement->execute(array(
                                    ':title'=>$title,
                                    ':category' => $category, 
                                    ':frecency' => $frecency, 
                                    ':qty' => $qty, 
                                    ':id' => $id,                           
                                    ':isChecked' => $isChecked,
                                    ':numClicks' => $numClicks,
                                    ':firstClick' => $firstClick,
                                    ':lastClick' => $lastClick
                ));
            } catch (Exception $e) {
                //NOT YET IMPLEMENTED
            }           
        }
        //restore environment   
        header("Location: index.php", true, 301);      
    }
?>
<?php //Show edit item window
    if (isset($_POST['editItem'])) {
        // if (isset($_POST['checkBox'])) {echo $_POST['checkBox'];}else{echo "no set";}
        // exit;
        //initialize data variables
        $id = '';
        // $title = '';
        // $category = '';
        $editFrecency = -1;
        $editQty = 1;
        // $isChecked = 'off';
        // $numClicks = 0;
        // $firstClick = '';
        // $lastClick = '';

        
        //Get id from form
        if (isset($_POST['editId'])) {
            $id = $_POST['editId'];
        } 
        //Get editItem values from db that are not in post variable
        $editItemObject = getListItemById($db, $id); 
        
        //populate variables for edit form
        if (isset($_POST['editTitle'])) {
            $editTitle = $_POST['editTitle'];
        }              
         
        if (isset($_POST['editQty'])) {
            $editQty = (int)$_POST['editQty'];
        }        
        if (isset($_POST['frecency'])) {
            $frecency = (int)$_POST['frecency'];
        }        

        // //if item checked, set click values
        // if (isset($_POST['checkBox'])) {
        //     if ($_POST['checkBox'] == 'on') {
        //         $isChecked = 1;
        //         $numClicks = 1;
        //         $firstClick = time();
        //         $lastClick = time();
        //     } else {
        //         $isChecked = 0;
        //         $numClicks = 0;
        //     }
        // }
        
        // //add the item
        // try {
        //     $query = "INSERT INTO ListItems (title, category, frecency, id, isChecked, numClicks, firstClick, lastClick)
        //                         VALUES (:title, :category, :frecency, :id, :isChecked, :numClicks, :firstClick, :lastClick);";
        //     $statement = $db->prepare($query);
        //     $statement->execute(array(
        //                         ':title'=>$title,
        //                         ':category' => $category, 
        //                         ':frecency' => $frecency, 
        //                         ':id' => $id,                          
        //                         ':isChecked' => $isChecked,
        //                         ':numClicks' => $numClicks,
        //                         ':firstClick' => $firstClick,
        //                         ':lastClick' => $lastClick
        //     ));
        // } catch Exception $e {
        //     //NOT YET IMPLEMENTE
        // }

        // //restore environment
        // $_POST = [];
        // header("Location: index.php", true, 301);
    }
?>
<?php 
    if (isset($_POST['itemDelete'])) {
        $id = $_POST['editId'];
        $query = "DELETE FROM ListItems WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(array(":id"=>$id));
        $listItems=getList($db);
        header("Location: index.php", true, 301);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,300i,400" rel="stylesheet">
    <link rel="shortcut icon" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script type="text/javascript" src="js/script.js"></script>

    <title>'Frecent' ListMaker</title>
</head>
<body>   
    <section class="list">      
        <h1>My 'Frecent' List</h1>
        <br> 
        <hr>
        <br>
        <div class="list__search">
            <div class="list__search--input"><input type="text" class="list__search--input-input" placeholder="search list"><img src = "./img/searchIcon.png" class="list__search--input-icon" alt="Search Icon Magnifying glass"></div>
        </div>
        <div class="list__addItem">
            <button class="btn btn__secondary" onClick="document.getElementById('js--addItemForm').style.display = 'block';">Add Item</button>
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
                                        <?php if(strtolower($row['category'])==strtolower($editItemObject['category'])) {$selected = 'selected';} ?>
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
                            <label for="addFrecency">'Frecency' (0-20 rarely; 20-80 sometimes; 80+ often)</label>   
                            <input name="addFrecency" type="text" value="<?php echo $editItemObject['frecency']; ?>"/>
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
                        <div class="" hidden><input id="js--addFrecencyEdit" type="text" name="addItemFrecencyEdit"></div>
                        <div class="" hidden><input id="js--addFrecencyEdit" type="text" name="id" value="<?php echo isset($editItemObject) ? $editItemObject['id'] : ''; ?>"></div>
                    </div>
                    <div class="flex list__addItem--addItemForm-submitButtons">  
                        <input type="submit" class="btn btn__primary" name="addEditSave" id="js--saveAddEditItem" value="Save"/>
                        <input type="submit" class="btn btn__secondary" name="addEditCancel" id="js--cancelEditAddItem" onClick="document.getElementById('js--addItemForm').style.display = 'none';" value="Cancel"/>
                    </div>    
                </form>  
            </div>
        </div> 
        <form action="index.php" method="post" name="viewByOrderBy">
            <div class="list__orderBy">
                <div class="list__orderBy--header">Order By:</div>
                <div class="list__orderBy--btns">                   
                        <input type="submit" class="btn btn__secondary btn__width125" name="frecency" value="Frecency"/>
                        <input type="submit" class="btn btn__secondary btn__width125" name="alpha" value="Alphabetical"/>
                        <input type="submit" class="btn btn__secondary btn__width125" name="category" value="Category"/>              
                </div>                
            </div>
            <div class="list__filterBy">
                <div class="list__filterBy--header">View By:</div>
                <div class="list__filterBy--btns">
                    <input type='submit' class="btn btn__secondary btn__width125" name="viewAll" value="All"/>
                    <input type='submit' class="btn btn__secondary btn__width125" name="checked" value="Checked"/>
                    <input type='submit' class="btn btn__secondary btn__width125" name="unChecked" value="Unchecked"/>               
                </div>
            </div>
        </form>
        <div class="list__container">
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
                                $frecencyWord = getFrecencyWord($item['frecency']);
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
                                    <div class="list__container--items-itemTitle"><input type="text" value="<?php echo $item['title']; ?>"></div>            
                                    <div class="list__container--items-itemTitlePreEdit" hidden><input type="text" name="editTitle" value="<?php echo $item['title']; ?>"></div>            
                                    <div class="list__container--items-itemCheckBox"><input type="checkbox" name="checkBox" <?php echo $checked ?>></div>                                  
                                    <button type='submit' class="list__container--items-itemEdit js--editItem"  name="editItem"><img src="./img/editItemIcon.png" alt="Pencil icon for edit list item"></button>                                  
                                    <button type='submit' class="list__container--items-itemDelete" name='itemDelete'><img src="./img/deleteRedX.png" name="deleteItem" alt="Big red X icon for delete list item"></button>  
                                    <div class="list__container--items-frecency" hidden><input type="text" name='editId' value="<?php echo $item['frecency']; ?>"></div>                                                   
                                    <div class="list__container--items-itemId" hidden><input type="text" name='editId' value="<?php echo $item['id']; ?>"></div>                                                   
                                </div>    
                            </div>
                        </form>    
                    <?php endforeach; ?> 
                </div>                            
            </div>                
        </div>        
    </section>
</body>
</html>