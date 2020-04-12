<?php

/**
* Use this command to create a new supergroup
* @author @zKeGod - @GoddeHouse
*
* Syntax: {alias}newgroup title [ < Optional]
* Alias list {/ .}
*
*/

if (strpos($update['message']['message'], 'newgroup')===+1 and in_array($update['message']['message'][0], ['/', '.'])) {
    if (!isset(explode(' ', $update['message']['message'], 2)[1])) {
        $title = 'New Group - Userbot';
    } else {
        $title = explode(' ', $update['message']['message'], 2)[1];
    }
    try {
        $group = yield $MadelineProto->channels->createChannel(['megagroup' => true, 'title' => $title, 'about' => '']);
        $text = "➕ <b>New Group Created</b>";
        $text .= "\n💭 <b>Title:</b> ".htmlspecialchars($title);
        if (isset($group['updates'][1]['channel_id'])) {
            try {
                $link = yield $MadelineProto->messages->exportChatInvite(['peer' => '-100'.$group['updates'][1]['channel_id']])['link'];
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
