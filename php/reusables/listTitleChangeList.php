
<h1>
    <?php echo 
        (isset($listInfo['listName'])) 
        ? $listInfo['listName'] 
        : "My 'Frecent' List"; 
    ?>
</h1>
<?php if 
    (isset($_SESSION['userInfo']['premium']) 
    && $_SESSION['userInfo']['premium']) {
        echo "<div 
            id='js--changeListButton' 
            class='list__selectList--changeListButton' 
            onClick='premiumView()'
            >
                Change List
            </div>"
        ;
    } 
?>
<!-- if change list selected, display list choices -->
<form 
    action="index.php" 
    name="changeListSelectBox" 
    class='list__selectList' 
    id="js--selectList" 
    method='post'
>
    <select 
        name="selectList" 
        onChange="this.classList.remove('list__selectList--active'); 
            document.querySelector('#js--changeListButton').style.display='inline-block'; 
            this.form.submit();
        "
    >
        <?php foreach($userLists as $row) : ?>                     
            <?php $selected = ""; ?> 
            <?php if(strtolower($row['listId'])==strtolower($_SESSION['listId'])) {
                    $selected = 'selected';
                } 
            ?>
            <option value="<?php echo $row['listId']; ?>" <?php echo $selected; ?>>
                <?php echo $row['listName']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Submit" hidden>
</form>