<?php
/**
 * Plugin Name: Discord Plugin
 * Description: Plugin to send on discord new comment
 * Version: 1.0
 **/

function show_message_function($comment_ID, $comment_approved)
{
    if (1 === $comment_approved) {
        $comment = get_comment($comment_ID);
        $post_id = $comment->comment_post_ID;
        $title = get_the_title($post_id);
        $author = $comment->comment_author;
        $message = $comment->comment_content;
        $webhookurl = "https://discord.com/api/webhooks/934894540500897855/doS37LLXXP7Z8CGvpz1wyM3wVP1dXb0MrhH0NqucYUMcAD284axJjEoA_L8uOS6TN4uA";

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

                    "color" => hexdec( "3366ff" ),

                    "author" => [
                        "name" => "Author: " . $author,
                    ],
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


        $ch = curl_init( $webhookurl );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );
        curl_close( $ch );
    }
}

add_action('comment_post', 'show_message_function', 10, 2);