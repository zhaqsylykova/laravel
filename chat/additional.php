<?php

namespace Websocket;

use App\Models\ChatMessageFile;
use Carbon\Carbon;
use phpDocumentor\Reflection\Type;


function sendTo($id, $messageData, $connections)
{
    if (isset($connections[$id])) {
        $connections[$id]->send(json_encode($messageData));
    };
}

function send($messageData, $connection)
{
    $connection->send(json_encode($messageData));
}

function sendAll($messageData, $connections)
{
    $message = json_encode($messageData);
    foreach ($connections as $c) {
        $c->send($message);
    }
}

function chat($fromId, $toId , $role)
{
    dump("user_id: $fromId : $toId");
    if ($fromId == $toId) {
        return false;
    }
    dump("user_id: $fromId");
    if($role == 'user'){
        $chatId = DB::table('chat_participants')
            ->where('user_id', $fromId)
            ->where('owner_id', $toId)
            ->where('role' , $role)
            ->pluck('chat_id')
            ->first();
    }else{
        $chatId = DB::table('chat_participants')
            ->where('user_id', $toId)
            ->where('owner_id', $fromId)
            ->where('role' , 'user')
            ->pluck('chat_id')
            ->first();
    }


    dump("chat_id: $chatId");

    if ($chatId == null) {
        $chatId = DB::table('chats')->insertGetId(['type' => 'user']);

        DB::table('chat_participants')->insert([
            'chat_id' => $chatId,
            'user_id' => $fromId,
            'owner_id' => $toId,
            'role' => $role

        ]);

    }

    return $chatId;

}

function message($messageData, $connection, $connections)
{
    $chatId = chat($connection->user_id, $messageData['to'] , $connection->role);
    $message['action'] = $messageData['action'];
    $message['role'] = $connection->role;
    $message['chat_id'] = $chatId;
    $message['name'] = $connection->name;
    $message['text'] = $messageData['text'];
    $message['created_at'] = Carbon::now()->addHours(4)->format('Y-m-d H:i:s');
    $message['user_id'] = $connection->user_id ?? null;
    $message['avatar'] = $connection->avatar ?? null;

   $messageID = DB::table('chat_messages')->insertGetId([
        'chat_id' => $chatId,
        'user_id' => $connection->user_id,
        'text' => $messageData['text'] ?? null,
        'created_at' => Carbon::now()->addHours(8)->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->addHours(8)->format('Y-m-d H:i:s'),
    ]);

    if($messageData['path'] != null){
      DB::table('chat_message_files')->insertGetId([
                'message_id' => $messageID,
                'path' => $messageData['path'],
        ]);
    }

    sendTo($messageData['to'], $message, $connections);
    send($message, $connection);

    DB::table('chats')
        ->where('id', $chatId)
        ->update(['updated_at' => Carbon::now()->addHours(5)]);

    return $message;

}

function fileMessage($messageData, $connection, $connections)
{

    dump('chat_file true');
    $chatId = chat($connection->user_id, $messageData['to'] , $connection->role);
    $messageID = DB::table('chat_messages')->insertGetId([
        'action' => 'fileMessage',
        'chat_id' => $chatId,
        'user_id' => $connection->user_id,
        'text' => $messageData['text'] ?? null,
        'created_at' => Carbon::now()->addHours(8)->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->addHours(8)->format('Y-m-d H:i:s'),
    ]);

    if($messageData['path'] != null){
        $image  = new ChatMessageFile();
        $image->type = $messageData['type'];
        $image->path = $messageData['path'];
        $image->message_id = $messageID ?? null;
        $image->save();

    }


    $message['action'] = 'fileMessage';
    $message['role'] =  $connection->role;
    $message['chat_id'] = $chatId;
    $message['name'] = $connection->name;
    $message['text'] = $messageData['text'] ?? null;
    $message['created_at'] = Carbon::now()->addHours(4)->format('Y-m-d H:i:s');
    $message['path'] = $image->path;
    $message['type'] = $messageData['type'];
    $message['user_id'] = $connection->user_id ?? null;
    $message['avatar'] = $connection->avatar ?? null;
    DB::table('chat_messages')->where('id', $messageData['message_id'])->update(['action' => 'fileMessage']);

    sendTo($messageData['to'], $message, $connections);
    send($message, $connection);

    DB::table('chats')->where('id', $message['chat_id'])->update(['updated_at' => Carbon::now()->addHours(6)]);



    return $message;

}




//function sendPushNotification($fields)
//{
//    dump('push', $fields);
//    // Set POST variables
//    $url = 'https://fcm.googleapis.com/fcm/send';
//
//    $headers = array(
//        'Authorization: key=AAAAMiFAybc:APA91bFSjkYIjM5vC94xTmaAi2GZHH_tyWf-kJC-7QC3uabt4YUCT3egKrrdMttl1wwiHoNPZJrXnsx6VJjZAGjyksEwjp9S0p3WhPJ6Y5sBUIV4Ly8K6ZiAzFZdS0x0hbSM7gwRtp2x',
//        'Content-Type: application/json'
//    );
//    // Open connection
//    $ch = curl_init();
//
//    // Set the url, number of POST vars, POST data
//    curl_setopt($ch, CURLOPT_URL, $url);
//
//    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//    // Disabling SSL Certificate support temporarly
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//
//    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//
//    // Execute post
//    $result = curl_exec($ch);
//    // echo "Result".$result;
//    if ($result === FALSE) {
//        die('Curl failed: ' . curl_error($ch));
//    }
//
//    // Close connection
//    curl_close($ch);
//
//    return $result;
//}
