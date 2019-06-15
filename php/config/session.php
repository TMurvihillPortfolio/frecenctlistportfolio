<?php
    //initialize session
    if (!isset($_SESSION)) {
        session_start();
    }

    //initialize session variables
    if (!isset($_SESSION['userInfo'])) {
        $_SESSION['userInfo'] = [];
    }
    if (!isset($_SESSION['userId'])) { //leftover from early versions
        $_SESSION['userId'] = '';
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
    if (!isset($_SESSION['scrollPosition'])) {
        $_SESSION['scrollPosition'] = '';
    }
    if (!isset($_SESSION['customCategories'])) {
        $_SESSION['customCategories'] = '';
    }

