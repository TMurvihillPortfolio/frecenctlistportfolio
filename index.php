<?php include_once 'php/config/session.php'; ?>
<?php include_once 'php/config/config.php'; ?>
<?php include_once 'php/classes/Database.php'; ?>
<?php include_once 'php/reusables/queries.php'; ?>
<?php include_once 'php/reusables/helpers.php'; ?>
<?php //redirect to login if needed
    $loginNeeded = true;
    if (isset($_SESSION['userInfo']['userId']) && $_SESSION['userInfo']['userId'] !== '') {
        $loginNeeded = false;
    } else {
        header("Location: login.php");  
        exit();
    }
?>
<?php //"change list" button clicked
    if (isset($_POST['selectList'])) {
        $_SESSION['listId'] = (int)$_POST['selectList'];
        unset($_POST['selectList']);
    }
?>
<?php //get userInfo, lists and categories
    //Get Categories - Query dependency: php/reusables/queries.php
    if ($_SESSION['userInfo']['premium']==0) {
        $categories=getCategories($db);
    } else if ($_SESSION['userInfo']['premium']==1) {
        $categoryString=getCustomCategories($db);
        $categories = explode('|', $categoryString);
        $_SESSION['customCategories'] = $categories;
    }
    //reset session orderBy if user selected a reorder button
    if (!isset($_SESSION['orderBy']) || $_SESSION['orderBy'] == '') $_SESSION['orderBy'] = 'category';
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
<?php //"add/edit item" button clicked
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
            $listItemId = time().mt_rand().$_SESSION['userInfo']['userId'];

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
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'php/reusables/head.php'; ?>
<body>
<div class="outer">
    <!-- store scroll position variable for repositioning after edit/delete -->
    <div id="js--scrollPositionDiv" hidden>
        <?php if (isset($_SESSION['scrollPosition'])) {
            echo $_SESSION['scrollPosition']; 
            $_SESSION['scrollPosition'] = '0';
        } ?>
    </div>
    <!-- display main navigation -->
    <?php include 'php/reusables/mainnav.php'; ?>
    <!-- display list -->
    <section class="list">
        <!-- display list title, change list premium option -->
        <?php include 'php/reusables/listTitleChangeList.php'; ?>
        <br><hr><br>
        <!--If user message, show user message -->
        <?php include 'php/reusables/messageToUser.php'; ?>
        <!-- If add/edit button clicked, show Add/Edit Item Window -->
        <?php include 'php/reusables/addEditItemWindow.php'; ?>
        <!-- orderBy and viewBy buttons-->
        <?php include 'php/reusables/orderViewByButtons.php'; ?>
        <!-- List Container -->
        <?php include 'php/reusables/listContainer.php'; ?>      
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