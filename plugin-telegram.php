<?php
/*
Plugin Name: Ygram
Plugin URI: https://git.ytrack.learn.ynov.com/CADDARIO/project_challenge_php
Description: Plugin for Ynov projet.
Author: Clément Addario
Version: 1.0
Author URI: https://github.com/ClementAdd
*/



$apiToken = "5057919374:AAHOaQoblgzAo2xANn7yg95OI901XYr6M8U";
$data = [
    'chat_id' => '-1001739056597',
    'text' => 'Le script fonctionne !'
];
$response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );