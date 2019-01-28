<?php
    function getFrecencyWord($frecency) {
        switch ($frecency) {
            case $frecency <= 20 :
                return "rarely";
            case $frecency > 20 && $frecency < 80 :
                return "sometimes";
            case $frecency >= 80 :
                return "often";
            default :
                return "not found";
        }
    }