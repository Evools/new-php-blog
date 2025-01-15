<?php

require_once __DIR__ . '/router.php';

get('/', 'pages/index.php');


any('/404', 'views/404.php');
