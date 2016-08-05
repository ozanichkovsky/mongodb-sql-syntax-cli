<?php

namespace MongoSql\Service;

use MongoSql\Parser\ParserFacadeInterface;

class SqlToMongoService {

    private $parserFacade;

    public function __construct(ParserFacadeInterface $parserFacade) {
        $this->parserFacade = $parserFacade;
    }

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