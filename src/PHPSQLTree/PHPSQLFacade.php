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

    /**
     * Set SQL parser
     *
     * @param PHPSQLParser $phpSqlParser
     */
    public function setSqlParser(PHPSQLParser $phpSqlParser) {
        $this->phpSqlParser = $phpSqlParser;
    }

    /**
     * Set collection parser
     *
     * @param ParserInterface $collectionParser
     */
    public function setCollectionParser(ParserInterface $collectionParser) {
        $this->collectionParser = $collectionParser;
    }

    /**
     * Set query parser
     *
     * @param ParserInterface $queryParser
     */
    public function setQueryParser(ParserInterface $queryParser) {
        $this->queryParser = $queryParser;
    }

    /**
     * Set projection parser
     *
     * @param ParserInterface $projectionParser
     */
    public function setProjectionParser(ParserInterface $projectionParser) {
        $this->projectionParser = $projectionParser;
    }

    /**
     * Set sort parser
     *
     * @param ParserInterface $sortParser
     */
    public function setSortParser(ParserInterface $sortParser) {
        $this->sortParser = $sortParser;
    }

    /**
     * Set skip parser
     *
     * @param ParserInterface $skipParser
     */
    public function setSkipParser(ParserInterface $skipParser) {
        $this->skipParser = $skipParser;
    }

    /**
     * Set limit parser
     *
     * @param ParserInterface $limitParser
     */
    public function setLimitParser(ParserInterface $limitParser) {
        $this->limitParser = $limitParser;
    }

    /**
     * Parse SQL and generate AST
     *
     * @param $sql
     * @throws MSE
     */
    public function parse($sql) {
        $sql = str_replace(' SKIP ', ' OFFSET ', $sql);

        $this->queryTree = $this->phpSqlParser->parse($sql);
    }

    /**
     * @inheritdoc
     */
    public function getCollectionName() {
        $this->validateTree();
        return $this->collectionParser->parse($this->queryTree);
    }

    /**
     * @inheritdoc
     */
    public function getQuery() {
        $this->validateTree();
        return $this->queryParser->parse($this->queryTree);
    }

    /**
     * @inheritdoc
     */
    public function getProjection() {
        $this->validateTree();
        return $this->projectionParser->parse($this->queryTree);
    }

    /**
     * @inheritdoc
     */
    public function getSort() {
        $this->validateTree();
        return $this->sortParser->parse($this->queryTree);
    }

    /**
     * @inheritdoc
     */
    public function getSkip() {
        $this->validateTree();
        return $this->skipParser->parse($this->queryTree);
    }

    /**
     * @inheritdoc
     */
    public function getLimit() {
        $this->validateTree();
        return $this->limitParser->parse($this->queryTree);
    }

    /**
     * @inheritdoc
     */
    private function isParsed() {
        return !is_null($this->queryTree);
    }

    /**
     * @inheritdoc
     */
    private function validateTree() {
        if (!$this->isParsed())
            throw new MSE('SQL was not parsed');
    }
}