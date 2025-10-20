<?php
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
