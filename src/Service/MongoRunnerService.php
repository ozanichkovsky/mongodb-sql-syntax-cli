<?php

namespace MongoSql\Service;

use MongoDB\Client;

class MongoRunnerService {

    private $client;

    private $db;

    public function __construct(Client $client, $db) {
        $this->client = $client;
        $this->db = $db;
    }

    public function execute($params) {
        $collection = $params['collection'];
        unset($params['collection']);

        $query = $params['query'];
        unset($params['query']);

        return $this->client->{$this->db}->$collection->find($query, $params);
    }
}