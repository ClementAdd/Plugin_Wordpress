<?php
/*
Plugin Name: ProjetPHP
Plugin URI: https://git.ytrack.learn.ynov.com/CADDARIO/project_challenge_php
Description: Vous devez créer un plugin Discord ou Télégram pour WordPress. Le plugin doit être capable (au minimum) de récupérer les commentaires d’un post pour les publier sur un canal Discord / Télégram précis. L’objectif est de recevoir une notification Discord / Télégram pour chaque commentaire posté.
Author: Clément Addario & Joris Pader
Version: 1.1
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
    add_option('webhook');

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


function notif_discord($comment_ID, $comment_approved)
{
    if (1 === $comment_approved) {
        $comment = get_comment($comment_ID);
        $post_id = $comment->comment_post_ID;
        $title = get_the_title($post_id);
        $author = $comment->comment_author;
        $message = $comment->comment_content;
        $webhookurl = get_option('webhook');

        $timestamp = date("c", strtotime("now"));

        $json_data = json_encode([

            "username" => "WordPress",
            "tts" => false,

            "embeds" => [
                [
                    "title" => "Title: " . $title,

                    "type" => "rich",

                    "description" => "New comment: " . $message,

                    "timestamp" => $timestamp,

                    "color" => hexdec("3366ff"),

                    "author" => [
                        "name" => "Author: " . $author,
                    ],
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);


        $ch = curl_init($webhookurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);
    }
}

add_action('comment_post', 'notif_discord', 10, 2);


function display_admin_page()
{
    ?>
    <h1>
        <?php esc_html_e('Notification Telegram', 'notif-telegram'); ?>
    </h1>

    <form action="admin.php?page=telegram" method="post">


        <input type="text" name="chatID" placeholder="ChatId telegram">

        <h1>
            <?php esc_html_e('Notification Discord', 'notif_discord'); ?>
        </h1>

        <input type="text" name="webhook" placeholder="Discord WebHook">


        <input type="submit" name="submit">

        <?php

        $id = "";
        if (isset($_POST['submit'])) {
            $id = $_POST['chatID'];
            echo 'ChatID Actuel : ' . $id;
        }
        if (strlen($id) > 10) {
            $options = update_option('chatID', $id);

        }
        ?>


        <?php

        $webhookurl = "";
        if (isset($_POST['submit'])) {
            $webhookurl = $_POST['webhook'];
            echo 'Webhook: ' . $webhookurl;
        }
        if (strlen($webhookurl) > 10) {
            $options = update_option('webhook', $webhookurl);

        }
        ?>
    </form>
    <?php

}

