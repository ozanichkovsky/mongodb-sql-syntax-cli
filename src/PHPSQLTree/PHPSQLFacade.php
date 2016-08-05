<?php

namespace MongoSql\PHPSQLTree;

use PHPSQLParser\PHPSQLParser;

use MongoSql\MongoSqlException as MSE;
use MongoSql\Parser\ParserFacadeInterface;

class PHPSQLFacade implements ParserFacadeInterface {

    /**
     * @var array
     */
    private $queryTree;

    /**
     * @var PHPSQLParser
     */
    private $phpSqlParser;

    /**
     * @var ParserInterface
     */
    private $collectionParser;

    /**
     * @var ParserInterface
     */
    private $queryParser;

    /**
     * @var ParserInterface
     */
    private $projectionParser;

    /**
     * @var ParserInterface
     */
    private $sortParser;

    /**
     * @var ParserInterface
     */
    private $skipParser;

    /**
     * @var ParserInterface
     */
    private $limitParser;

    public function setSqlParser(PHPSQLParser $phpSqlParser) {
        $this->phpSqlParser = $phpSqlParser;
    }

    public function setCollectionParser(ParserInterface $collectionParser) {
        $this->collectionParser = $collectionParser;
    }

    public function setQueryParser(ParserInterface $queryParser) {
        $this->queryParser = $queryParser;
    }

    public function setProjectionParser(ParserInterface $projectionParser) {
        $this->projectionParser = $projectionParser;
    }

    public function setSortParser(ParserInterface $sortParser) {
        $this->sortParser = $sortParser;
    }

    public function setSkipParser(ParserInterface $skipParser) {
        $this->skipParser = $skipParser;
    }

    public function setLimitParser(ParserInterface $limitParser) {
        $this->limitParser = $limitParser;
    }

    public function parse($sql) {
        $sql = str_replace(' SKIP ', ' OFFSET ', $sql);

        $this->queryTree = $this->phpSqlParser->parse($sql);

        if (!is_array($this->queryTree))
            throw new MSE('Wrong SQL provided');
    }

    public function getCollectionName() {
        $this->validateTree();
        return $this->collectionParser->parse($this->queryTree);
    }

    public function getQuery() {
        $this->validateTree();
        return $this->queryParser->parse($this->queryTree);
    }

    public function getProjection() {
        $this->validateTree();
        return $this->projectionParser->parse($this->queryTree);
    }

    public function getSort() {
        $this->validateTree();
        return $this->sortParser->parse($this->queryTree);
    }

    public function getSkip() {
        $this->validateTree();
        return $this->skipParser->parse($this->queryTree);
    }

    public function getLimit() {
        $this->validateTree();
        return $this->limitParser->parse($this->queryTree);
    }

    private function isParsed() {
        return !is_null($this->queryTree);
    }

    private function validateTree() {
        if (!$this->isParsed())
            throw new MSE('SQL was not parsed');
    }
}