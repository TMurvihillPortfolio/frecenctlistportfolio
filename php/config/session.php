<?php
    //initialize session
    session_start();
    //initialize session variables
    if (!$_SESSION['list']) {
        $_SESSION['list'] = '';
    }
    if (!$_SESSION['orderBy']) {
        $_SESSION['orderBy'] = '';
    }
    if (!$_SESSION['viewBy']) {
        $_SESSION['viewBy'] = '';
    }

