<?php

/**
* Use this command to check if the userbot is online
* @author @zKeGod - @GoddeHouse
*
* Just send in chat {alias}ping
* Alias list {/ .}
*/

if (strpos($update['message']['message'], 'ping')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    $text = "💭 <b>Online</b>\n⌛️ <b>Calculating Ping...</b>";
    $start = microtime(true);
    $var = yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
    $text = "💭 <b>Online</b>\n⏱ <b>Ping:</b> <code>".(round((microtime(true) - $start), 3))." ms</code>";
    yield $MadelineProto->messages->editMessage(['peer' => $chatID, 'id' => $var['id'], 'text' => $text, 'parse_mode' => 'html']);
}
