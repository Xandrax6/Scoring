<?php
    $PW2_CONFIG = array(
        'db_scheme' => 'MySQL',
        'db_info' => array(
            'host' => 'localhost',
            'user' => 'root',
            'pass' => 'toor',
            'db' => 'phpwatch'
        ),
        'path' => dirname(__FILE__),
    );

    define('PW2_VERSION', '2.0.4 Beta');
    define('PW2_PATH', $PW2_CONFIG['path']);
?>