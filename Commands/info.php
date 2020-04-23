<?php

/**
* Use this command to get info about users
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}info
* Usage: Via reply | username | ID
* Alias list {/ .}
*
*/

if (strpos($update['message']['message'], 'info')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    if (!isset(explode(' ', $msg)[1]) and !isset($update['message']['reply_to_msg_id'])) {
        $text = "⚠️ <b>Invalid Syntax</b>";
        $text .= "\n➖ <code>Use this command via reply or with username | ID</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
    } elseif (isset($update['message']['reply_to_msg_id'])) {
        if ($chatID < 0) {
            $getMessage = yield $MadelineProto->channels->getMessages(['channel' => $chatID, 'id' => [$update['message']['reply_to_msg_id']]]);
        } else {
            $getMessage = yield $MadelineProto->messages->getMessages(['id' => [$update['message']['reply_to_msg_id']]]);
        }
        $id = $getMessage['users'][0]['id'];
    } elseif (isset(explode(' ', $update['message']['message'])[1])) {
        try {
            $getInfo = yield $MadelineProto->getInfo(explode(' ', $update['message']['message'])[1]);
        } catch (\danog\MadelineProto\RPCErrorException $e) {
            $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
            return yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        } catch (Exception $e) {
            $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->getMessage())."</code>";
            return yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        }
        if ($getInfo['type'] == 'user') {
            $id = $getInfo['User']['id'];
        } else {
            $text = "⚠️ <b>Error:</b> <code>USER_INVALID</code>";
            return yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        }
    }
    $getInfo = yield $MadelineProto->getInfo($id)['User'];
    $arr = ['1' => '✔️','0' => '✖️'];
    $text = "🔎 <b>User's Information</b>";
    $text .= "\n\n  • 💭 <b>Name:</b> <code>".htmlspecialchars($getInfo['first_name'])."</code>";
    if (isset($getInfo['last_name'])) {
        $text .= "\n  • 🏷 <b>Last Name:</b> <code>".htmlspecialchars($getInfo['last_name'])."</code>";
    }
    $text .= "\n  • 💡 <b>ID:</b> <code>".$getInfo['id']."</code>";
    if (isset($getInfo['username'])) {
        $text .= "\n  • ⚙️ <b>Username:</b> @".$getInfo['username'];
    }
    $text .= "\n  • 🤖 <b>Bot:</b> ".$arr[$getInfo['bot']];
    if (isset($getInfo['photo']['dc_id'])) {
        $dc = ['1' => '🌎 1 - America', '2' => '🌍 2 - Europe', '3' => '🌎 3 - America', '4' => '🌍 4 - Europe', '5' => '🌏 5 - Asia'];
        $text .= "\n  • 📡 <b>DataCenter:</b> ".$dc[$getInfo['photo']['dc_id']];
    }
    $text .= "\n\n  • 🔗 <b>Link:</b> <a href='tg://user?id=".$getInfo['id']."'>".$getInfo['id']."</a>";
    yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id'], 'disable_web_page_preview' => true]);
}
