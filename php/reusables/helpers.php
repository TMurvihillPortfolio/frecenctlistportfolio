<?php
    //take frecency number and return frecency word (rarely, sometimes, often)
    function getFrecencyWord($frecencyNumber) {
        switch ($frecencyNumber) {
            case $frecencyNumber <= 20 :
                return "rarely";
            case $frecencyNumber > 20 && $frecencyNumber < 80 :
                return "sometimes";
            case $frecencyNumber >= 80 :
                return "often";
            default :
                return "not found";
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
            default :
                return 0;
        }
    }