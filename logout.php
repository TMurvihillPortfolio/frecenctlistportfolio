<?php session_start(); ?>
<?php include 'php/reusables/helpers.php'; ?>
<?php
    logout();
    header('Location: index.php');
    exit();