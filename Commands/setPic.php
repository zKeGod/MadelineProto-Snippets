<?php

/**
* Use this command to set a new profile pic
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}setpic
* Usage: reply to a photo
* Alias list {/ .}
*
*/

if (strpos($update['message']['message'], 'setpic')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    if (!isset($update['message']['reply_to_msg_id'])) {
        $text = "⚠️ <b>Invalid Syntax</b>";
        $text .= "\n➖ <code>Use this command replying to a photo</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
    } else {
        if ($chatID < 0) {
            $pic = yield $MadelineProto->channels->getMessages(['channel' => $chatID, 'id' => [$update['message']['reply_to_msg_id']]]);
        } else {
            $pic = yield $MadelineProto->messages->getMessages(['id' => [$update['message']['reply_to_msg_id']]]);
        }
        if (isset($pic) and isset($pic['messages'][0]['media']['photo'])) {
            try {
                yield $MadelineProto->download_to_file($pic['messages'][0]['media']['photo'], $userID.".jpg");
                $file = yield $MadelineProto->upload($userID.".jpg");
                yield $MadelineProto->photos->updateProfilePhoto(['file' => $file]);
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '✔️ <b>Profile pic Updated</b>', 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
                unlink($userID.".jpg");
            } catch (\danog\MadelineProto\RPCErrorException $e) {
                $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            } catch (Exception $e) {
                $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->getMessage())."</code>";
                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
            }
        } else {
            $text = "⚠️ <b>Invalid Syntax</b>";
            $text .= "\n➖ <code>Use this command replying to a photo</code>";
            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        }
    }
}
