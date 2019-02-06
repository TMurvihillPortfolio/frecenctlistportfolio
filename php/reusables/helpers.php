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
                return 18;
            case 'sometimes' :
                return 10;
            case 'rarely' :
                return 2;
            case 'one-time Purchase' :
                return 0;
            default :
                return 0;
        }
    }

    //Calculate the number of click for a given frecency. Master formula = numClicks * currentClickPeriod (from config file) /timeSinceFirstClick
    function calculateClicks($firstClick, $frecency=0, $frecencyInterval) {
        
        //return 0 if no frecency number
        if ($frecency == 0 || $frecency == '') {
            return 0;
        } 
        //get time in days since first click
        $frecencyIntervalsSinceFirstClick = ((strtotime(Date("Y-m-d")) - strtotime($firstClick))/86400)/100                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             ;

        if ($frecencyIntervalsSinceFirstClick == 0 || $frecencyIntervalsSinceFirstClick == '') {
            return 0;
        }
        //return number of clicks for the given frecency
        return round($frecencyIntervalsSinceFirstClick*($frecency / $frecencyInterval));   //frecencyInterval can be found in config file    
    }
    //Calculate the frecency for a given numClicks and timePeriod. Master formula = numClicks * currentClickPeriod (from config file) /timeSinceFirstClick
    function calculateFrecency($numClicks, $firstClick, $frecencyInterval) {
        //get time in days since first click
        $frecencyIntervalsSinceFirstClick = (((strtotime(date("Y-m-d"))-strtotime($firstClick))/86400)/100);
        $clicksPerInterval = $numClicks/$frecencyIntervalsSinceFirstClick;
        
        //return 0 if no frecency number
        if ($numClicks == 0 || $numClicks == '') {
            return 0;
        }
        //return 0 if no time since first and lastClick
        if ($frecencyIntervalsSinceFirstClick == 0 || $frecencyIntervalsSinceFirstClick == '') {
            return 0;
        }
            
        //return number of clicks for the given frecency
        //sql version  of calculation for queries: frecency = ((numClicks/((DATE()-firstClick)/86400))/100)*5 
        return round($clicksPerInterval*$frecencyInterval); //frecencyInterval can be found in config file   
        //return 24;          
    }

    //adjust frecency number for display for very large and very small numbers
    function frecencyDisplay($frecencyNum) {
        if ($frecencyNum == 0) return 0;
        if ($frecencyNum < 1) return "<1";
        if ($frecencyNum > 100) return "100+";
        return round($frecencyNum);
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
            unset($_SESSION['list']);
            unset($_SESSION['id']);   
            session_destroy();
        }
    
    }
?>