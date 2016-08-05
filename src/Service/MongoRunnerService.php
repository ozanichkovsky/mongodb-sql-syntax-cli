<?php

namespace MongoSql\Service;

use MongoDB\Client;

/**
 * Class MongoRunnerService
 * @package MongoSql\Service
 */
class MongoRunnerService {

    /**
     * @var Client
     */
    private $client;

    /**
     * MongoDB database name
     *
     * @var string
     */
    private $db;

    /**
     * MongoRunnerService constructor.
     * @param Client $client
     * @param $db
     */
    public function __construct(Client $client, $db) {
        $this->client = $client;
        $this->db = $db;
    }

    /**
     * Execute MongoDB find query
     *
     * @param $params
     * @return \MongoDB\Driver\Cursor
     */
    public function execute($params) {
        $collection = $params['collection'];
        unset($params['collection']);

        $query = $params['query'];
        unset($params['query']);

        return $this->client->{$this->db}->$collection->find($query, $params);
    }
}