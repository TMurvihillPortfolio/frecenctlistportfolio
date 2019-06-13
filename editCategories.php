<?php include 'php/config/config.php'; ?>
<?php include 'php/config/session.php'; ?>
<?php include 'php/classes/Database.php'; ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php
    $categoryArray;
    $categoryString = '';
    //get custom category list
    $query = "SELECT * FROM customCategories WHERE custCatUserId=:id";
    $statement = $db->prepare($query);
    $statement->execute(array(':id'=>$_SESSION['userInfo']['userId']));
    
    if (!$categories=$statement->fetchAll(PDO::FETCH_ASSOC)) {
        //NOT YET IMPLEMENTED error
        $result = "User custom categories not found.";
        echo $result;
        exit();
    } else {
        foreach ($categories as $row) {
            $categoryArray = explode('|', $row['custCatList']);
            $categoryString = $row['custCatList'];
        }
    }
?>
<!DOCTYPE html>
<html>
<?php include 'php/reusables/head.php'; ?>

<body>   
    <div class="outer">
        <?php include 'php/reusables/mainnav.php'; ?>
        <div class="profile__line1">
                <h1>Categories<span>Page</span></h1>
        </div>
        <div class='signatureBox editCategories'>
            <h2>Categories</h2>
            <h4>Drag and drop to reorder</h4>
            <br>
            <hr>
            <br>
            <div id="container"> <!-- this div is a dependency of 'getChildIndex' function in editCategories.js -->
            <?php foreach ($categoryArray as $arrayItem) : ?>
                <div class='editCategories__listItem dropTarget'>
                    <div class="flex editCategories__listItem--dragGrouping childPointerNone" draggable="true"><p><?php echo $arrayItem ?></p><img src='./img/dragDropBars.png' draggable=false /></div>
                </div>
            <?php endforeach; ?>
            </div>
            <div id="js--PHPArrayTransfer" hidden><?php echo $categoryString ?></div>       
        </div>
          
    </div>
     <!-- update number of clicks API -->
     <script>
        function updateCategoryOrder(catList) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function (){               
                //NOT YET implemented Message to user box on error
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("js--tempId").style.background-color = 'green';
                    //document.getElementById("js--tempId").innerHTML = this.responseText;
                } else {
                    //document.getElementById("js--tempId").style.background-color = 'red';
                    //document.getElementById("js--tempId").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "php/reusables/updateCategoryOrderAPI.php?catlist=" + catList, true);
            xmlhttp.send();       
        }   
    </script>
    <script type="text/javascript" src="js/editCategories.js"></script>
</body>
</html>