<?php

/**
* Use this command to send a message to all chats (Channels are excluded)
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}post text
* Alias list {/ .}
*
*/

if (strpos($update['message']['message'], 'post')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    if (!isset(explode(' ', $update['message']['message'], 2)[1])) {
        $text = "⚠️ <b>Invalid Syntax</b>";
        $text .= "\n➖ <code>".$update['message']['message'][0]."post text</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
    } else {
        $text = explode(' ', $update['message']['message'], 2)[1];
        $var = yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => "🌐 <b>I'm starting...</b>", 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        $chats = yield $MadelineProto->getFullDialogs();
        $count = 0;
        foreach ($chats as $chat_id => $jsonValue) {
            # THE USERBOT WILL NOT SEND IN CHANNELS
            if ($jsonValue['peer']['_'] != 'peerChannel') {
                try {
                    yield $MadelineProto->messages->sendMessage(['peer' => $chat_id, 'message' => $text, 'parse_mode' => 'MARKDOWN']);
                    $count++;
                } catch (Exception $e) {
                }
            }
        }
        $text = "✅ <b>Message sent</b>\n💭 <b>Chats Count:</b> <code>".$count."</code>";
        yield $MadelineProto->messages->editMessage(['peer' => $chatID, 'id' => $var['id'], 'text' => $text, 'parse_mode' => 'html']);
    }
}
