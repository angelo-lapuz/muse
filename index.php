<?php
session_start();


//print_r(@parse_url($_SERVER['REQUEST_URI']));
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
        require 'log_in.php';
        break;
    case '/log_in':
        require 'log_in.php';
        break;
    case '/main':
        require 'main.php';
        break;
    case '/postValidation':
        require 'postValidation.php';
        break;
    default:
        http_response_code(404);
        exit('Not Found');
}

?>