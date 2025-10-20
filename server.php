<?php
// // backend/server.php
// // Run: php server.php

// require __DIR__ . '/vendor/autoload.php';

// use Ratchet\MessageComponentInterface;
// use Ratchet\ConnectionInterface;
// use Ratchet\Http\HttpServer;
// use Ratchet\WebSocket\WsServer;
// use Ratchet\Server\IoServer;

// class EventSocket implements MessageComponentInterface {
//     /** @var \SplObjectStorage */
//     protected $clients;

//     public function __construct() {
//         $this->clients = new \SplObjectStorage;
//         echo "WebSocket server starting on port 8080\n";
//     }

//     public function onOpen(ConnectionInterface $conn) {
//         $this->clients->attach($conn);
//         echo "New connection: {$conn->resourceId}\n";
//     }

//     public function onMessage(ConnectionInterface $from, $msg) {
//         // We expect JSON messages; handle gracefully if not.
//         $decoded = null;
//         try {
//             $decoded = json_decode($msg, true);
//         } catch (\Throwable $e) {
//             $decoded = null;
//         }

//         // Example: broadcast the received message to all other clients
//         foreach ($this->clients as $client) {
//             if ($client !== $from) {
//                 // Send original raw message (so non-JSON still works)
//                 $client->send($msg);
//             }
//         }
//     }

//     public function onClose(ConnectionInterface $conn) {
//         $this->clients->detach($conn);
//         echo "Connection {$conn->resourceId} closed\n";
//     }

//     public function onError(ConnectionInterface $conn, \Exception $e) {
//         echo "Error: {$e->getMessage()}\n";
//         $conn->close();
//     }
// }

// $port = 8080;
// $server = IoServer::factory(
//     new HttpServer(
//         new WsServer(
//             new EventSocket()
//         )
//     ),
//     $port
// );

// $server->run();

require __DIR__ . '/vendor/autoload.php'; // Composer autoload

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Your Ratchet WebSocket handler
class MyWebSocketHandler implements \Ratchet\MessageComponentInterface {
    public function onOpen(\Ratchet\ConnectionInterface $conn) {
        echo "New connection! ({$conn->resourceId})\n";
    }
    public function onMessage(\Ratchet\ConnectionInterface $from, $msg) {
        echo "Message received: $msg\n";
        $from->send("Echo: $msg");
    }
    public function onClose(\Ratchet\ConnectionInterface $conn) {
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    public function onError(\Ratchet\ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Use Render-provided port
$port = getenv('PORT') ?: 8080;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyWebSocketHandler()
        )
    ),
    $port
);

echo "WebSocket server running on port $port\n";
$server->run();
