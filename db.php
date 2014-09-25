<?php
    $GLOBALS['db'] = new PDO("mysql:host=localhost;dbname=txt", "root", "root", array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true));
    $GLOBALS['db']->exec("SET NAMES 'UTF8';");
    $GLOBALS['db']->exec("SET CHARACTER_SET_RESULTS='UTF8';");