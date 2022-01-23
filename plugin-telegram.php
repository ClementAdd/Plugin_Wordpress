<?php
/*
Plugin Name: Ygram
Plugin URI: https://git.ytrack.learn.ynov.com/CADDARIO/project_challenge_php
Description: Plugin for Ynov projet.
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
        'telegram_notif',
        'https://cdn-icons.flaticon.com/png/128/3670/premium/3670044.png?token=exp=1642770390~hmac=84d28deab6081bdcc22bc15bd82c3eae',
        20
    );
}

add_action('admin_menu', 'my_admin_menu');

function show_message_function($comment_ID, $comment_approved)
{
    if (1 === $comment_approved) {
        $comment = get_comment($comment_ID);
        $author = $comment->comment_author;
        $subject = 'Nouveau commentaire de :' . ucfirst($author);
        $message = $comment->comment_content;
        telegram_notif($subject . "\n" . $message);
    }
}

add_action('comment_post', 'show_message_function', 10, 2);


function telegram_notif($comment)
{
    $apiToken = "5057919374:AAHOaQoblgzAo2xANn7yg95OI901XYr6M8U";
    $data = [
        'chat_id' => '-1001739056597',
        'text' => $comment
    ];
    $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data));


    ?>
    <h1>
        <?php esc_html_e('Notification Telegram', 'notif-telegram'); ?>
    </h1>
    <?php
}



