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
    function calculateClicks($firstClick, $frecency=0, $frecencyInterval) {
        //return 0 if no frecency number
        if ($frecency == 0 || $frecency == '') {
            return 0;
        } 
        //get time in days since first click
        $frecencyIntervalsSinceFirstClick = (strtotime("now") - strtotime($firstClick))/86400;
        //return number of clicks for the given frecency
        return round(($frecency*$frecencyIntervalsSinceFirstClick) / $frecencyInterval);   //frecencyPeriodInDays can be found in config file    
    }
    //Calculate the frecency for a given numClicks and timePeriod. Master formula = numClicks * currentClickPeriod (from config file) /timeSinceFirstClick
    function calculateFrecency($numClicks, $firstClick, $lastClick, $frecencyInterval) {
        //get time in days since first click
        $frecencyIntervalsSinceFirstClick = (strtotime($lastClick) - strtotime($firstClick))/86400;

        //return 0 if no frecency number
        if ($numClicks == 0 || $numClicks == '') {
            return 0;
        }
        //return 0 if no time since first and lastClick
        if ($frecencyIntervalsSinceFirstClick == 0 || $frecencyIntervalsSinceFirstClick == '') {
            return 0;
        }
            
        //return number of clicks for the given frecency
        //return round(($numClicks*$frecencyInterval) / $frecencyIntervalsSinceFirstClick); //frecencyPeriodInDays can be found in config file 
        return round(($numClicks) / $frecencyIntervalsSinceFirstClick); //frecencyPeriodInDays can be found in config file 
            
    }