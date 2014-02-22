<?php
require_once __DIR__ . '/../vendor/autoload.php';

$render = new Render('layout.phtml');
$render->name = 'Tim';
$render->message = 'This is a secret message';
$render->encode = function($value){
    return str_rot13($value);
};

echo $render('template.phtml');