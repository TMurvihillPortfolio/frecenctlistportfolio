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
                <?php include 'php/reusables/listItem.php'; ?>
            <?php endforeach; ?> 
        </div>                            
    </div>                
</div>