<?php
/*
Plugin Name: ProjetPHP
Plugin URI: https://git.ytrack.learn.ynov.com/CADDARIO/project_challenge_php
Description: Vous devez créer un plugin Discord ou Télégram pour WordPress. Le plugin doit être capable (au minimum) de récupérer les commentaires d’un post pour les publier sur un canal Discord / Télégram précis. L’objectif est de recevoir une notification Discord / Télégram pour chaque commentaire posté.
Author: Clément Addario & Joris Pader
Version: 1.1
Author URI: https://github.com/ClementAdd
*/

//Plugin initialization
function init_plugin() {
    add_option( 'chatID' );
    add_option( 'webhook' );
    add_option( 'bot_comment' );
    add_option( 'bot_name' );
    add_option( 'bot_message' );
    add_option( 'bot_author' );
}

register_activation_hook( __FILE__, 'init_plugin' );

#region Telegram

function telegram_notif( $comment = "" ) {
    $chatID   = get_option( 'chatID' );
    $apiToken = "5057919374:AAHOaQoblgzAo2xANn7yg95OI901XYr6M8U";
    $data     = [
        'chat_id' => $chatID,
        'text'    => $comment
    ];
    if ( $comment != '' ) {
        $response = file_get_contents( "https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query( $data ) );
        if ( $response == http_response_code( 400 ) ) {
            exit();
        }
    }
}

function show_message_function( $comment_ID, $comment_approved ) {
    if ( 1 === $comment_approved ) {
        $comment = get_comment( $comment_ID );
        $author  = $comment->comment_author;
        $subject = 'Nouveau commentaire de : ' . ucfirst( $author );
        $message = $comment->comment_content;
        telegram_notif( $subject . "\n" . $message );
    }
}

add_action( 'comment_post', 'show_message_function', 10, 2 );

#endregion

//Discord
function discord_notif( $comment_ID, $comment_approved ) {
    if ( 1 === $comment_approved ) {
        $comment    = get_comment( $comment_ID );
        $post_id    = $comment->comment_post_ID;
        $timestamp = date( "c", strtotime( "now" ) );
        $author = $comment->comment_author;

        $bot_name    = get_option( 'bot_name' ) == "" ? "Bot" : get_option( 'bot_name' );
        $bot_content = get_option( 'bot_message' ) == "" ? null : get_option( 'bot_message' );
        $bot_comment = get_option( 'bot_comment' ) == "" ? "Comment" : get_option( 'bot_comment' );
        $bot_author = get_option( 'bot_author' ) == "" ? "Author" : get_option( 'bot_comment' );

        $json_data = json_encode( [

            "username" => $bot_name,
            "tts"      => false,
            "content"  => $bot_content,

            "embeds" => [
                [
                    "title" => "Title of post: " . get_the_title( $post_id ),

                    "type" => "rich",

                    "description" =>   $bot_comment . ": " . $comment->comment_content,

                    "timestamp" => $timestamp,

                    "color" => hexdec( "3366ff" ),

                    "author" => [
                        "name" => $bot_author . ": " . ucfirst($author),
                    ],
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

        $ch = curl_init( get_option( 'webhook' ) );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-type: application/json' ) );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

        $response = curl_exec( $ch );
        curl_close( $ch );
    }
}

add_action( 'comment_post', 'discord_notif', 10, 2 );

//Dashboard Menu
function notification_admin_menu() {
    add_menu_page( 'Notifications Telegram', 'Telegram', 'manage_options', 'notifications-admin-menu-telegram', 'notification_admin_menu_telegram', 'dashicons-format-chat', 2 );
    add_submenu_page( 'notifications-admin-menu-telegram', 'Discord', 'Discord', 'manage_options', 'notifications-admin-menu-discord', 'notifications_admin_menu_discord' );
}

add_action( 'admin_menu', 'notification_admin_menu' );

//Telegram Page
function notification_admin_menu_telegram() {
    ?>
    <h1>
        <?php esc_html_e( 'Notification Telegram', 'notif-telegram' ); ?>
    </h1>

    <form action="admin.php?page=notifications-admin-menu-telegram" method="post">

        <input type="text" name="chatID" minlength="5" placeholder="<?php if(get_option('chatID') != null) echo get_option('chatID'); else echo "Entre ID"?>">

        <input type="submit" name="submit"  value="Save">

        <?php

        $id = "";
        if ( isset( $_POST['submit'] ) ) {
            $id = $_POST['chatID'];
            echo 'ChatID Actuel : ' . $id;
        }
        if ( strlen( $id ) > 10 ) {
            $options = update_option( 'chatID', $id );
        }
        ?>
    </form>
    <?php
}

//Discord Page
function notifications_admin_menu_discord() {
    ?>
    <form action="admin.php?page=notifications-admin-menu-discord" method="post">
        <div class="wrapper" style="display:grid;grid-template-columns: 400px 400px;">
            <div>
                <h1>
                    <?php esc_html_e( 'Discord WebHook', 'notif_discord' ); ?>
                </h1>
                <input type="text" name="webhook" minlength="32" placeholder="<?php if(get_option('webhook') != null) echo get_option('webhook'); else echo "Entre webhook"?>">
            </div>
            <div>
                <h1>
                    <?php esc_html_e( 'Bot message', 'bot_message' ); ?>
                </h1>
                <input type="text" name="bot_message" minlength="1" placeholder="<?php if(get_option('bot_message') != null) echo get_option('bot_message'); else echo "Entre message"?>">
            </div>
            <div>
                <h1>
                    <?php esc_html_e( 'Message for new comments', 'bot_comment' ); ?>
                </h1>
                <input type="text" name="bot_comment" minlength="1" placeholder="<?php if(get_option('bot_comment') != null) echo get_option('bot_comment'); else echo "Entre text"?>">
            </div>
            <div>
                <h1>
                    <?php esc_html_e( 'Author', 'bot_author' ); ?>
                </h1>
                <input type="text" name="bot_author" minlength="1" placeholder="<?php if(get_option('bot_author') != null) echo get_option('bot_author'); else echo "Entre text"?>">
            </div>
            <div>
                <h1>
                    <?php esc_html_e( 'Bot name', 'bot_name' ); ?>
                </h1>
                <input type="text" name="bot_name" minlength="1" placeholder="<?php if(get_option('bot_name') != null) echo get_option('bot_name'); else echo "Entre name"?>">
            </div>
        </div>
        <br>
        <input type="submit" name="submit"  value="Save">

        <?php
        $webhookurl = "";
        $bot_comment = "";
        $bot_name = "";
        $bot_message = "";
        $bot_author = "";

        if ( isset( $_POST['submit'] ) ) {
            $webhookurl = $_POST['webhook'];
            $bot_comment = $_POST['bot_comment'];
            $bot_name = $_POST['bot_name'];
            $bot_message = $_POST['bot_message'];
            $bot_author = $_POST['bot_author'];
            echo "Settings save";
        }

        if( $webhookurl != "" && $webhookurl != get_option('webhook'))
            $options = update_option( 'webhook', $webhookurl );

        if( $bot_comment != "" && $bot_comment != get_option('bot_comment'))
            $options = update_option( 'bot_comment', $bot_comment );

        if( $bot_name != "" && $bot_name != get_option('bot_name'))
            $options = update_option( 'bot_name', $bot_name );

        if( $bot_message != "" && $bot_message != get_option('bot_message'))
            $options = update_option( 'bot_message', $bot_message );

        if( $bot_author != "" && $bot_author != get_option('bot_author'))
            $options = update_option( 'bot_author', $bot_author );
        ?>
    </form>
    <?php
}