<?php
//session_start();
require_once('databaseQuery.php');

//print_r(@parse_url($_SERVER['REQUEST_URI']));
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/log_in':
    case '/':
        require 'log_in.php';
        break;
    case '/main':
        require 'main.php';
        break;
    case '/postValidation':
        require 'postValidation.php';
        break;
    case '/register':
        require 'register.php';
        break;
    case '/logout':
        require 'logout.php';
        break;
    default:
        http_response_code(404);
        exit('Not Found');
}

?>