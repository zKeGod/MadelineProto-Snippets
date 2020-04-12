<?php

/**
* Use this command to get a random shiba
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}shiba
* Alias list {/ .}
*
*/


if (strpos($update['message']['message'], 'shiba')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    $result = json_decode(file_get_contents("http://shibe.online/api/shibes?count=[1-100]&urls=[true/false]&httpsUrls=[true/false]"), true)[0];
    if ($result) {
        $URL = "https://cdn.shibe.online/shibes/".$result.".jpg";
        try {
            yield $MadelineProto->messages->sendMedia(['peer' => $chatID, 'media' => ['_' => 'inputMediaUploadedPhoto', 'file' => $URL], 'message' => '🐕 Here\'s your Shiba!']);
        } catch (\danog\MadelineProto\RPCErrorException $e) {
            $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
            return yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        } catch (Exception $e) {
            $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->getMessage())."</code>";
            return yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
        }
    } else {
        $text = "⚠️ <b>Error:</b> <code>Unable to reach the API</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id'], 'disable_web_page_preview' => true]);
    }
}
