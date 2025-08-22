<?php
$token = '7985968026:AAHoNcDbNimVpToWxoYlDskFoBajQ03T5Uc';
$response = file_get_contents("https://api.telegram.org/bot{$token}/getUpdates");
header('Content-Type: application/json; charset=UTF-8');
echo $response;