<?php

/**
* Use this command to join in a group, supergroup or channel
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}join target_chat
* Alias list {/ .}
*
*/

if (strpos($update['message']['message'], 'join')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    if (!isset(explode(' ', $update['message']['message'])[1])) {
        $text = "⚠️ <b>Invalid Syntax</b>";
        $text .= "\n➖ <code>".$update['message']['message'][0]."join invite_link</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
    } else {
        $invite_link = explode(' ', $update['message']['message'])[1];
        if (strpos($invite_link, "@")===0) {
            try {
                yield $MadelineProto->channels->joinChannel(['channel' => $invite_link]);
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => "✅ <b>Joined</b>", 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            } catch (\danog\MadelineProto\RPCErrorException $e) {
                $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            } catch (\danog\MadelineProto\Exception $e) {
                $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->getMessage())."</code>";
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            }
        } else {
            try {
                yield $MadelineProto->messages->importChatInvite(['hash' => $invite_link]);
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => "✅ <b>Joined</b>", 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            } catch (\danog\MadelineProto\RPCErrorException $e) {
                if ($e->rpc == 'INVITE_HASH_INVALID') {
                    $e->rpc = 'INVITE_LINK_INVALID';
                }
                $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            } catch (\danog\MadelineProto\Exception $e) {
                $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->getMessage())."</code>";
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            }
        }
    }
}
