<?php

namespace Websocket;
require_once "../vendor/autoload.php";
require_once "./database.php";
require_once "./auth.php";
require_once "./additional.php";


use App\Models\User;
use Carbon\Carbon;
use Websocket\DB;

use Workerman\Worker;
use Workerman\Lib\Timer;


use function GuzzleHttp\describe_type;

//
//$context = array(
//    'ssl' => array(
//        'local_cert'  => '/etc/letsencrypt/live/icomment.life/fullchain.pem',
//        'local_pk'    => '/etc/letsencrypt/live/icomment.life/privkey.pem',
//        'verify_peer' => false,
//    )
//);

$worker = new Worker('websocket://0.0.0.0:9595/');
//$worker->transport = 'ssl';
$connections = [];
// 4 processes
$worker->count = 1;

$worker->onConnect = function ($connection) use (&$connections) {
    $connection->onWebSocketConnect = function ($connection) use (&$connections) {
        if (isset($_GET['user_id'])) {
            $connections = signIn($connections, $connection);
        } else {
            send([
                'action' => 'notAuthorized',
            ], $connection);
            return;
        }

//        $connection['events']['action'] = function() {
//        };
//        $connection['events']['action']();
    };
};

$worker->onMessage = function ($connection, $message) use (&$connections) {
//    $messageData = json_decode($message, true);
//        dump($messageData );
//    dump($connection->role);
//    switch ($messageData['action']) {
//        case 'pong':
//            $connection->pingWithoutResponseCount = 0;
//            break;
//        case 'read':
//            $messageData['action'] = 'read';
//            DB::table('chat_messages')
//                ->where('user_id', '!=', $connection->user_id)
//                ->where('chat_id', $messageData['chat_id'])
//                ->update(['read' => 1]);
//            break;
//        case 'message':
//            dump($connection->id);
//            message($messageData, $connection, $connections);
//            break;
//        case 'fileMessage':
//            fileMessage($messageData, $connection, $connections);
//            break;
//    }


};

$worker->onWorkerStart = function ($worker) use (&$connections) {
//    $interval = 10; // пингуем каждые 5 секунд
//
//    Timer::add($interval, function() use(&$connections) {
//        foreach ($connections as $c) {
//            // Если ответ от клиента не пришел 3 раза, то удаляем соединение из списка
//            // и оповещаем всех участников об "отвалившемся" пользователе
//            if ($c->pingWithoutResponseCount >= 3) {
//                $messageData = [
//                    'action' => 'ConnectionLost',
//                    'id' => $c->id,
//                    'name' => $c->name,
//                    'login' => $c->login,
//                    'avatar' => $c->avatar
//                ];
//                $message = json_encode($messageData);
//
//                unset($connections[$c->id]);
//                $c->destroy(); // уничтожаем соединение
//
//                // рассылаем оповещение
//                foreach ($connections as $c) {
//                    $c->send($message);
//                }
//            }
//            else {
//                $c->send('{"action":"ping"}');
//                $c->pingWithoutResponseCount++; // увеличиваем счетчик пингов
//            }
//        }
//    });
};

$worker->onClose = function ($connection) use (&$connections) {
    // Эта функция выполняется при закрытии соединения
    if (!isset($connections[$connection->id])) {
        return;
    }
    $offline = User::find($connection->id);
    $offline->online = 0;
    $offline->save();
    // Удаляем соединение из списка
    unset($connections[$connection->id]);

    // Оповещаем всех пользователей о выходе участника из чата
//    $messageData = [
//        'action' => 'disconnected',
//        'id' => $connection->id,
//        'name' => $connection->name,
////        'login' => $connection->login,
////        'avatar' => $connection->avatar
//    ];
//    $message = json_encode($messageData);
//
//    foreach ($connections as $c) {
//        $c->send($message);
//    }

    dump("disconnect :  $connection->id");


};


Worker::runAll();

