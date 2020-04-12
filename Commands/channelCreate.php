<?php

/**
* Use this command to create a new channel
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}newchannel title [ < Optional]
* Alias list {/ .}
*
*/

if (strpos($update['message']['message'], 'newchannel')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    if (!isset(explode(' ', $update['message']['message'], 2)[1])) {
        $title = 'New Channel - Userbot';
    } else {
        $title = explode(' ', $update['message']['message'], 2)[1];
    }
    try {
        $channel = yield $MadelineProto->channels->createChannel(['broadcast' => true, 'title' => $title, 'about' => '']);
        $text = "➕ <b>New Channel Created</b>";
        $text .= "\n💭 <b>Title:</b> ".htmlspecialchars($title);
        if (isset($channel['updates'][1]['channel_id'])) {
            try {
                $link = yield $MadelineProto->messages->exportChatInvite(['peer' => '-100'.$channel['updates'][1]['channel_id']])['link'];
            } catch (Exception $e) {
            }
            if ($link) {
                $text .= "\n🔗 <b>Invite Link:</b> <a href='".$link."'>here</a>";
            }
        }
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id'], 'disable_web_page_preview' => true]);
    } catch (\danog\MadelineProto\RPCErrorException $e) {
        $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->rpc)."</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
    } catch (Exception $e) {
        $text = "⚠️ <b>Error:</b> <code>".htmlspecialchars($e->getMessage())."</code>";
        yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => $text, 'parse_mode' => 'html', 'reply_to_msg_id' => $update['message']['id']]);
    }
}
