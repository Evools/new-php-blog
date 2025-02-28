<?php

require_once __DIR__ . '/router.php';

get('/', 'pages/index.php');

get('/sign-up', 'pages/auth/sign-up.php');
post('/sign-up', 'pages/auth/sign-up.php');

get('/sign-in', 'pages/auth/sign-in.php');


any('/404', 'pages/404.php');
