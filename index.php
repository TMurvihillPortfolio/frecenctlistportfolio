<?php include_once 'php/config/session.php'; ?>
<?php include_once 'php/config/config.php'; ?>
<?php include_once 'php/classes/Database.php'; ?>
<?php include_once 'php/reusables/listQuery.php'; ?>
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
?>
<?php
    if (isset($_POST['addEditSave'])) {
        $id = time() . mt_rand() . 'tmurv'; //NOT YET IMPLEMENTED -- needs the userId to replace 'tmurv'
        $title = $_POST['addTitle'];
        $category = $_POST['addCategory'];
        $frecency = getFrecencyNumber($_POST['addFrecency']);
        $increment = 1;
        if (isset($_POST['checkBox'])) {
            $increment = $_POST['increment'];
        } 
        $isChecked = 'off';
        if (isset($_POST['checkBox'])) {
            $isChecked = $_POST['checkBox'];
        }    
        $numClicks = 0;
        $firstClick;
        $lastClick;
        if ($isChecked = 'on') {
            $isChecked = 1;
            $numClicks = 1;
            $firstClick = time();
            $lastClick = time();
        } else {
            $isChecked = 0;
            $numClicks = 0;
        }
        //echo $title.'|'.$category.'|'.$frecency.'|'.$id.'|'.$isChecked.'|'.$numClicks.'|'.$firstClick.'|'.$lastClick;
        $query = "INSERT INTO ListItems (title, category, frecency, id, increment, isChecked, numClicks, firstClick, lastClick)
                                VALUES (:title, :category, :frecency, :id, :increment, :isChecked, :numClicks, :firstClick, :lastClick);";
        $statement = $db->prepare($query);
        $statement->execute(array(
                            ':title'=>$title,
                            ':category' => $category, 
                            ':frecency' => $frecency, 
                            ':id' => $id,
                            ':increment' => $increment,                           
                            ':isChecked' => $isChecked,
                            ':numClicks' => $numClicks,
                            ':firstClick' => $firstClick,
                            ':lastClick' => $lastClick
        ));
        $_POST['addEditSave'] = '';
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
            <div class="list__addItem--addItemForm" id="js--addItemForm">                 
                <div>
                    <h3>Add/Edit List Item</h3>                       
                </div>
                <hr>  
                <!-- Add/Edit Item Form  -->
                <form name="addItem" action="" method="post">                               
                    <div class="flexWrap">
                        <div class="list__addItem--addItemForm-qty"><label for="addQty">Qty</label><input name="addQty" type="text" id="js--addQty"></div>
                        <div class="list__container--items-itemCheckBox" checked><label for="checkBox">Check Item?</label><input type="checkbox" name="checkBox" id="js--addChecked" checked></div>
                        <div class="list__addItem--addItemForm-title"><label for="addTitle">Item Name</label><input name="addTitle" type="text" id="js--addItemTitle"></div>
                        <!-- <div class="showAddItemCategory"><label for="addCategory">Category</label><input name="addCategory" type="text" id="js--addCategory" placeholder="produce dairy etc"></div> -->
                        <div class="list__addItem--addItemForm-category">
                            <label for="addCategory">Category</label>
                            <select name="addCategory" id="js--addCategory">
                                    <option value="select">select category</option>
                                    <option value="produce">Produce</option>
                                    <option value="meat">Meat</option>
                                    <option value="dairy">Dairy</option>
                                    <option value="frozen foods">Frozen foods</option>
                                    <option value="breads">Breads</option>
                                    <option value="dry goods">Dry Goods</option>
                                    <option value="health and beauty">Health and Beauty</option>
                                    <option value="non-food">Non-food Items</option>
                                    <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="list__addItem--addItemForm-frecency">
                            <label for="addFrecency">Starting 'Frecency'</label>
                            <select name="addFrecency" id="js--addFrecencyRating">
                                <option value="often">Often</option>
                                <option value="sometimes" selected="selected">Sometimes</option>
                                <option value="rarely">Rarely</option>
                            </select>
                        </div>
                        <div class="list__addItem--addItemForm-increment"><label for="addIncrement">Increment</label> <input name="addIncrement" type="text" id="js--addIncrement"></div>
                        <div class="" hidden> <input name="addHidden" type="text" id="js--addIncrement"></div>
                        <div class="" hidden><input id="js--addFrecencyEdit" type="text" name="addItemFrecencyEdit"></div>
                    </div>
                    <div class="flex">  
                        <input type="submit" class="btn btn__primary" name="addEditSave" id="js--saveAddEditItem" value="Save"/>
                        <button type="button" class="btn btn__secondary" name="addEditCancel" id="js--cancelEditAddItem" style="display: inline-flex" onClick="document.getElementById('js--addItemForm').style.display = 'none';">Cancel</button>
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
                        <div class="list__container--items-item" data-id = "<?php echo $item['id']; ?>">
                            <div class="flex">
                                <div class="list__container--items-itemQty"><input type="text" value="<?php echo $item['qty']; ?>" disabled></div>
                                <div class="list__container--items-itemQtyPreEdit" hidden><input type="text" value="<?php echo $item['qty']; ?>" disabled></div>
                                <div class="list__container--items-itemTitle"><input type="text" onChange="toggleIsChecked('test2')" value="<?php echo $item['title']; ?>"></div>            
                                <div class="list__container--items-itemTitlePreEdit" hidden><input type="text" value="<?php echo $item['title']; ?>" disabled></div>            
                                <div class="list__container--items-itemCheckBox"><input type="checkbox" name="checkBox" <?php echo $checked ?>></div>
                                <div class="list__container--items-itemEdit js--editItem"><img src="./img/editItemIcon.png" name="editItem" alt="Pencil icon for edit list item"></div>                                  
                                <div class="list__container--items-itemDelete  js--deleteItem"><img src="./img/deleteRedX.png" name="deleteItem" alt="Trash can icon for delete list item"></div>                    
                            </div>    
                        </div>    
                    <?php endforeach; ?> 
                </div>                            
            </div>                
        </div>        
    </section>
</body>
</html>

<!-- <div class="list__container--items-item">
    <div class="list__container--items-itemQty"><input type="text" value="1" disabled></div>
    <div class="list__container--items-itemQtyPreEdit" hidden><input type="text" value="1" disabled></div>
    <div class="list__container--items-itemTitle"><input type="text" value="milk" disabled></div>            
    <div class="list__container--items-itemTitlePreEdit" hidden><input type="text" value="milk" disabled></div>            
    <div class="list__container--items-itemCheckBox"><input type="checkbox"></div>
    <div class="list__container--items-itemEdit js--editItem"><img src="/img/editItemIcon.png" name="editItem" alt="Pencil icon for edit list item"></div>                                  
    <div class="list__container--items-itemDelete  js--deleteItem"><img src="/img/deleteRedX.png" name="deleteItem" alt="Trash can icon for delete list item"></div>                    
</div>    
<div class="list__container--items-saveCancel" style="display:none">                             
    <button name="editSave" type="button" class="btn btn__primary">Save</button>
    <button name="editCancel" type="button" class="btn btn__secondary">cancel</button>
</div>  -->