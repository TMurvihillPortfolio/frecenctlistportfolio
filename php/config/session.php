<?php
    //initialize session
    session_start();
    //initialize session variables
    if (!isset($_SESSION['id'])) {
        $_SESSION['id'] = '';
    }
    if (!isset($_SESSION['listId'])) {
        $_SESSION['listId'] = '';
    }
    if (!isset($_SESSION['orderBy'])) {
        $_SESSION['orderBy'] = '';
    }
    if (!isset($_SESSION['viewBy'])) {
        $_SESSION['viewBy'] = '';
    }

