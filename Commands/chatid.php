<?php

/**
* Use this command to check the chat ID
* @author @zKeGod - @GoddeHouse
*
* Just send in chat {alias}chatid
* Alias list {/ .}
*/

if (strpos($update['message']['message'], 'chatid')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    $text = "💡 <b>Chat ID:</b> <code>".$chatID."</code>";
    yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
}
