<?php
namespace Websocket;
use App\Models\User;

require_once "./database.php";
require_once "./additional.php";



function signIn ($connections,$connection){

    $id = $_GET['user_id'];


    $user = DB::table('users')->where('id', $_GET['user_id'])->first();


    if (!$user){
        $messageData = [
            'action' => 'notAuthorized',
        ];
        $connection->send(json_encode($messageData));
        return;
    }else{

       $online = User::find($user->id);
       $online->online = 1;
       $online->save();
        dump("GET USER  ID : $id");
        $connection->id = $user->id;
        $connection->user_id = $user->id;
        $connection->name = $user->name;

        $connection->pingWithoutResponseCount = 0;


        $connections[$connection->id] = $connection;
//        $users = [];
//        foreach ($connections as $c) {
//            $users[] = [
//                'id' => $c->id,
//                'user_id' => $c->user_id,
//                'name' => $c->name,
//            ];
//        }


        send([
            'action' => 'authorized',
            'id' => $connection->id,
            'user_id' => $connection->user_id,
            'name' => $connection->name,
            'users' => null
        ],$connection);

//        sendAll([
//            'action' => 'connected',
//            'id' => $connection->id,
//            'user_id' => $connection->user_id,
//            'name' => $connection->name,
//        ],$connections);

//        dump("connection user_".$connection->user_id,);
//        dump('connect role'. $connection->role);

        return $connections;
    }

}

