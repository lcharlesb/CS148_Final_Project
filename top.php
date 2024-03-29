<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Git Together</title>
        <meta charset="utf-8">
        <meta name="author" content="Jake Mann, Mateo Riofrio and Luke Beatty">
        <meta name="description" content="Bob, Thank you for the example">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/base.css" type="text/css" media="screen">
        <link href="https://fonts.googleapis.com/css?family=Didact+Gothic&display=swap" rel="stylesheet">

        <?php
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // inlcude all libraries. 
        // 
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        print '<!-- begin including libraries -->';
        
        include 'lib/constants.php';

        include LIB_PATH . '/Connect-With-Database.php';

        print '<!-- libraries complete-->';
        ?>	

    </head>

    <!-- **********************     Body section      ********************** -->
    <?php
    print '<body id="' . $PATH_PARTS['filename'] . '">';
    include 'header.php';
    include LIB_PATH . '/validation_functions.php';
    include LIB_PATH . '/hash_function.php';
    include LIB_PATH . '/mail-message.php';
    require_once LIB_PATH . '/security.php';
    include 'isAdmin.php';
    ?>