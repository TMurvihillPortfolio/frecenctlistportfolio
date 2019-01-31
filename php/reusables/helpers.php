<?php
    //take frecency number and return frecency word (rarely, sometimes, often)
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
    
    //take frecency word (rarely, sometimes, often) and return frecency number
    function getFrecencyNumber($frecencyWord) {
        switch ($frecencyWord) {
            case 'often' :
                return 80;
            case 'sometimes' :
                return 50;
            case 'rarely' :
                return 20;
            case 'One-time Purchase' :
                return 0;
            default :
                return 0;
        }
    }