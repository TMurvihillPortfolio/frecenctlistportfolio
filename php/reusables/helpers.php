<?php
    //take frecency number and return frecency word (rarely, sometimes, often, one-time purchase)
    function getFrecencyWord($frecencyNumber) {
        if ($frecencyNumber == 0) {
            return "one-time purchase";
        } else if ($frecencyNumber > 0 && $frecencyNumber <= 20) {
            return "rarely";
        } else if ($frecencyNumber > 20 && $frecencyNumber < 80) {
            return "sometimes";
        } else if ($frecencyNumber >= 80) {
            return "often";
        }
    }
    
    //take frecency word (rarely, sometimes, often, one-time purchase) and return frecency number
    function getFrecencyNumber($frecencyWord) {
        switch ($frecencyWord) {
            case 'often' :
                return 90;
            case 'sometimes' :
                return 50;
            case 'rarely' :
                return 10;
            case 'one-time Purchase' :
                return 0;
            default :
                return 0;
        }
    }

    //Calculate the number of click for a given frecency. Master formula = numClicks * currentClickPeriod (from config file) /timeSinceFirstClick
    function calculateClicks($firstClick, $lastClick, $frecency=0, $frecencyInterval) {       
        //return 0 if no frecency number
        if ($frecency <= 0 || $frecency == '') {
            return 0;
        }

        //get time in seconds since first click
        $timeSinceFirstClick = time() - strtotime($firstClick);
        $numIntervals = round($timeSinceFirstClick / $frecencyInterval);
        //NOT YET IMPLEMENTED -- validate $timeSinceFirstClick
        if (!$numIntervals || $numIntervals < 1) {
            //NOT YET IMPLEMENTED error message that first date needs to be updated or was updated "error encountered, please edit item and reenter frecency"
            return 0;
        }
        $numClicks = $numIntervals * ($frecency/100);

        //penalize number of clicks for not recently clicked
        $numClicks = numClicksFrecency($lastClick, $numClicks);
        //return number of clicks for the given frecency
        return $numClicks;   //frecencyInterval can be found in config file    
    }
    //Calculate the frecency for a given numClicks and timePeriod. Master formula = numClicks * currentClickPeriod (from config file) /timeSinceFirstClick
    function calculateFrecency($numClicks, $firstClick, $frecencyInterval) {
        //return 0 if no clicks
        if ($numClicks == 0 || $numClicks == '') {
            return 0;
        }
        //caluclate number of intervals
        $timeSinceFirstClick = time() - strtotime($firstClick);
       
        //return 0 if no time since first and lastClick
        if (!$timeSinceFirstClick || $timeSinceFirstClick == '') {
            return 0;
        }

        $numIntervals = $timeSinceFirstClick / $frecencyInterval;
        
        //calculate frecency
        $frecency = round(($numClicks / $numIntervals)*100);
                
        return $frecency;











        
        // //get time in days since first click
        // $frecencyIntervalsSinceFirstClick = round(((strtotime(date("Y-m-d"))-strtotime($firstClick))/86400)/$frecencyInterval);
        // //$clicksPerInterval = $numClicks/$frecencyIntervalsSinceFirstClick;
        
        // //return 0 if no frecency number
        // if ($numClicks == 0 || $numClicks == '') {
        //     return 0;
        // }
        // //return 0 if no time since first and lastClick
        // if ($frecencyIntervalsSinceFirstClick == 0 || $frecencyIntervalsSinceFirstClick == '') {
        //     return 0;
        // }
        
        // //return frecency
        // return $frecencyIntervalsSinceFirstClick;
        // //sql version  of calculation for queries: frecency = ROUND((numClicks/((CURRENT_DATE-firstClick)/86400))/ :frecencyInterval) 
        // //return round($clicksPerInterval*$frecencyInterval); //frecencyInterval can be found in config file   
        return 50000; 
    }

    //adjust frecency number for display for very large and very small numbers
    function frecencyDisplay($frecencyNum) {
        if ($frecencyNum == 0) return 0;
        if ($frecencyNum < 1) return "<1";
        if ($frecencyNum > 100) return "100+";
        return round($frecencyNum);
    }

    //helper function for numClicksFrecency function. Finds out how many days since given date
    function numDaysOld($daysDate) {        
        $now = time();
        $your_date = strtotime($daysDate);
        $datediff = $now - $your_date;
        return round($datediff / (60 * 60 * 24));
    }

    //penalizes number of clicks if item has not been clicked recently
    function numClicksFrecency($lastClick, $numClicks) {
        $daysOld = numDaysOld($lastClick);
        
        if ($daysOld > 240) {
            $numClicks *= .05;
        } else if ($daysOld > 180) {
            $numClicks *= .20;
        } else if ($daysOld > 120) {
            $numClicks *= .35;
        } else if ($daysOld > 60) {
            $numClicks *= .8;
        }
        return $numClicks;
    }

    //Logout
    function logout(){
        // NOT YET IMPLEMENTED if(isset($_COOKIE['rememberUserCookie'])){
        //     uset($_COOKIE['rememberUserCookie']);
        //     setcookie('rememberUserCookie', null, -1, '/');
        // }
        if (isset($_SESSION)) {
            unset($_SESSION['viewBy']);
            unset($_SESSION['orderBy']);
            unset($_SESSION['listId']);
            unset($_SESSION['userId']);
            unset($_SESSION['editItemObject']);  
            session_destroy();
        }   
    }

    //Close Account
    function closeAccount($db, $closeAccountId) {
        $result = '';

        //find Lists associated with user    
        try {
            $splQuery = "Select * FROM Lists WHERE listUserId= :listUserId";
            $statement = $db->prepare($splQuery);
            $statement->execute(array(':listUserId'=>$_SESSION['userId']));
        }catch (PDOexception $ex) {
            $result = "An error occurred. Try logging out and logging in again.";
        }
        
        //delete listItems, lists and user    
        if($lists=$statement->fetchAll()){
            
            foreach ($lists as $list) {
            
                //delete listItems accociated with list
                try {
                    $statement = $db->prepare( "DELETE FROM ListItems WHERE listId =:listId" );
                    $statement->execute(array(':listId'=>$list['listId']));

                }catch (PDOexception $ex) {
                    $result = "An error occurred. Try logging out and logging in again.";
                }

                //delete list
                try {
                    $statement = $db->prepare("DELETE FROM Lists WHERE listId = :listId");
                    $statement->execute(array(':listId'=>$list['listId']));

                    if (!$statement->rowCount()) {
                        $result = "No list deleted: ".$list['listName'];               
                    } 
                }catch (PDOexception $ex) {
                    $result = "An error occurred. Try logging out and logging in again.";
                }        
            }

            //delete User
            try{
                $statement = $db->prepare("DELETE FROM users WHERE userId = :userId");
                $statement->execute(array(':userId'=>$_SESSION['userId']));

                if (!$statement->rowCount()) {
                $result = "Close account not successful for user Id#: ".$list['listUserId']."Try logging in again."; 
                //clean up environment
                header('Location: logout.php');             
                } else {
                    header('Location: logout.php');
                }
            }catch (PDOexception $ex) {
                $result = "An error occurred. Try logging out and logging in again.";
            }           
            return $result;
        }else{
            return "User or lists not found. Try logging out and logging in again.";
        }
    }

/***************
 * Validate input
 ***************/
//password
function validatePassword($password) {
    return strlen($password) >= 8 ? true : false;
}
//sanitize input from W3 schools
function testInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>