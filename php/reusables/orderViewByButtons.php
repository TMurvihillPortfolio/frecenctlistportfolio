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