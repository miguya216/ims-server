<?php
require __DIR__ . '/vendor/autoload.php'; // Composer autoload

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class MyWebSocketHandler implements \Ratchet\MessageComponentInterface {
    public function onOpen(\Ratchet\ConnectionInterface $conn) {
        echo "New connection! ({$conn->resourceId})\n";

        // Send welcome message as JSON
        $conn->send(json_encode([
            'type' => 'welcome',
            'message' => "Welcome! Your connection ID is {$conn->resourceId}"
        ]));
    }

    public function onMessage(\Ratchet\ConnectionInterface $from, $msg) {
        echo "Message received: $msg\n";

        // Send back a JSON message
        $from->send(json_encode([
            'type' => 'echo',
            'message' => $msg
        ]));
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
