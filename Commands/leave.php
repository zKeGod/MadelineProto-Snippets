<?php

/**
* Use this command to leave from a group, supergroup or channel
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}leave target_chat
* Alias list {/ .}
*
*/

if (strpos($update['message']['message'], 'leave')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    if (!isset(explode(' ', $update['message']['message'])[1])) {
        $target_group = $chatID;
    } else {
        $target_group = explode(' ', $update['message']['message'])[1];
    }
    try {
        $type = (yield $MadelineProto->getInfo($target_group))['type'];
    } catch (\danog\MadelineProto\RPCErrorException $e) {
        $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
        return yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $msgid]);
    } catch (Exception $e) {
        $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->getMessage())."</code>";
        return yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $msgid]);
    }
    if ($type == 'user') {
        $text = "⚠️ <b>Error:</b> <code>Use this command with a group / supergroup / channel</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $msgid]);
    } elseif ($type == 'chat') {
        try {
            yield $MadelineProto->messages->deleteChatUser(['chat_id' => $target_group, 'user_id' => (yield $MadelineProto->get_self()['id'])]);
            $text = '🚪 I have <b>left</b> the <b>group</b> ('.htmlspecialchars($target_group).')';
            yield $MadelineProto->messages->sendMessage(['peer' => $userID, 'message' => $text, 'parse_mode' => 'html', 'disable_web_page_preview' => true]);
        } catch (\danog\MadelineProto\RPCErrorException $e) {
            $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $msgid]);
        }
    } else {
        try {
            yield $MadelineProto->channels->leaveChannel(['channel' => $target_group]);
            $text = '🚪 I have <b>left</b> the <b>supergroup / channel</b> ('.htmlspecialchars($target_group).')';
            yield $MadelineProto->messages->sendMessage(['peer' => $userID, 'message' => $text, 'parse_mode' => 'html', 'disable_web_page_preview' => true]);
        } catch (\danog\MadelineProto\RPCErrorException $e) {
            $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $msgid]);
        }
    }
}
