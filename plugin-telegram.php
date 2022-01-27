<?php
/*
Plugin Name: Ygram
Plugin URI: https://git.ytrack.learn.ynov.com/CADDARIO/project_challenge_php
Description: Vous devez créer un plugin Discord ou Télégram pour WordPress. Le plugin doit être capable (au minimum) de récupérer les commentaires d’un post pour les publier sur un canal Discord / Télégram précis. L’objectif est de recevoir une notification Discord / Télégram pour chaque commentaire posté.
Author: Clément Addario
Version: 1.0.1
Author URI: https://github.com/ClementAdd
*/


function my_admin_menu()
{
    add_menu_page(
        __('Telegram notification', 'telegram-notification'),
        __('Telegram notification', 'telegram-notification'),
        'manage_options',
        'telegram',
        'display_admin_page',
        'dashicons-format-chat',
        20
    );
}

add_action('admin_menu', 'my_admin_menu');


function init_plugin()
{
    add_option('chatID');

}

register_activation_hook(__FILE__, 'init_plugin');


function show_message_function($comment_ID, $comment_approved)
{



    if (1 === $comment_approved) {
        $comment = get_comment($comment_ID);
        $author = $comment->comment_author;
        $subject = 'Nouveau commentaire de : ' . ucfirst($author);
        $message = $comment->comment_content;
        telegram_notif($subject . "\n" . $message);
    }
}

add_action('comment_post', 'show_message_function', 10, 2);


function telegram_notif($comment = "")
{


    $chatID = get_option('chatID');
    $apiToken = "5057919374:AAHOaQoblgzAo2xANn7yg95OI901XYr6M8U";
    $data = [
        'chat_id' => $chatID,
        'text' => $comment
    ];
    if ($comment != '') {
        $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data));
        if ($response == null) {
            exit();
        }
    }

}

function display_admin_page()
{
    ?>
    <h1>
        <?php esc_html_e('Notification Telegram', 'notif-telegram'); ?>
    </h1>

    <form action="admin.php?page=telegram" method="post">


        <input type="text" name="chatID" placeholder="ChatId telegram">

        <input type="submit" name="submit">

        <?php

        $id = "";
        if ( isset( $_POST['submit'] ) ) {
             $id = $_POST['chatID'];
            echo 'ChatID Actuel : '.$id;
        }

        $options = update_option('chatID', $id);
        ?>

    </form>

    <?php


}

