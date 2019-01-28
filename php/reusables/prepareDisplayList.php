<?php
    //filter by viewChecked
    if ($_SESSION['viewBy'] == 'checked' && $item['isChecked'] == false) continue;
    if ($_SESSION['viewBy'] == 'unChecked' && $item['isChecked'] == true) continue;
        
    //add a heading if needed                          
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