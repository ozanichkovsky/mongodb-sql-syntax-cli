<?php

namespace MongoSql\Service;

use MongoSql\Parser\ParserFacadeInterface;

/**
 * Class SqlToMongoService
 * @package MongoSql\Service
 */
class SqlToMongoService {

    /**
     * @var ParserFacadeInterface
     */
    private $parserFacade;

    /**
     * SqlToMongoService constructor.
     * @param ParserFacadeInterface $parserFacade
     */
    public function __construct(ParserFacadeInterface $parserFacade) {
        $this->parserFacade = $parserFacade;
    }

    /**
     * Parse SQL and get array accepted by @see MongoRunnerService
     *
     * @param $sql
     * @return array
     */
    public function parse($sql) {
        $parser = $this->parserFacade;

        $parser->parse($sql);

        $projection = $parser->getProjection();
        $sort = $parser->getSort();
        $limit = $parser->getLimit();
        $skip = $parser->getSkip();

        $result = [
            'collection' => $parser->getCollectionName(),
            'query' => $parser->getQuery()
        ];

        if (!is_null($projection)) {
            $result['projection'] = $projection;
        }

        if (!is_null($sort)) {
            $result['sort'] = $sort;
        }

        if (!is_null($limit)) {
            $result['limit'] = $limit;
        }

        if (!is_null($skip)) {
            $result['skip'] = $skip;
        }

        return $result;
    }
}