<?php

/**
* Use this command to check if an username is free
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}check username
* Alias list {/ .}
*
*/

if (strpos($update['message']['message'], 'check')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    if (!isset(explode(' ', $update['message']['message'])[1])) {
        $text = "⚠️ <b>Invalid Syntax</b>";
        $text .= "\n➖ <code>".$update['message']['message'][0]."check username</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
    } else {
        if (strpos(explode(' ', $update['message']['message'])[1], '@')===0) {
            $username = str_replace('@', '', explode(' ', $update['message']['message'])[1]);
        } else {
            $username = explode(' ', $update['message']['message'])[1];
        }
        try {
            $check = yield $MadelineProto->channels->checkUsername(['username' => $username]);
            if ($check) {
                $text = "✔️ <b>This username is free</b>";
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            } else {
                $text = "✖️ <b>This username is already taken</b>";
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            }
        } catch (\danog\MadelineProto\RPCErrorException $e) {
            $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        } catch (Exception $e) {
            $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->getMessage())."</code>";
            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        }
    }
}
