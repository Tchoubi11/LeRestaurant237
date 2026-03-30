<?php

namespace App\Service;

use MongoDB\Client;

class MongoService
{
    private $collection;

    public function __construct()
    {
        
        $client = new Client("mongodb://127.0.0.1:27017");

        $db = $client->selectDatabase('mon_app');
        $this->collection = $db->selectCollection('logs');
    }

    public function logAction(string $user, string $action, string $ip): void
    {
        $this->collection->insertOne([
            'user' => $user,
            'action' => $action,
            'ip' => $ip,
            'date' => new \DateTime()
        ]);
    }
}