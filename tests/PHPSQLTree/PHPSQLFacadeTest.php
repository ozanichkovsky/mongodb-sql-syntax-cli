<?php

namespace MongoSql\Tests\SqlTree;

use MongoSql\PHPSQLTree\PHPSQLFacade;
use MongoSql\PHPSQLTree\ParserInterface;
use MongoSql\Tests\Base;
use PHPSQLParser\PHPSQLParser;

class PHPSQLFacadeTest extends Base {

    private $parserInterfaceMock;

    /**
     * @var PHPSQLFacade
     */
    private $phpSqlFacade;

    public function setUp() {
        $this->phpSqlFacade = new PHPSQLFacade();

        $sqlPhpParseMock = $this->createMock(PHPSQLParser::class);

        $sqlPhpParseMock
            ->expects($this->any())
            ->method('parse')->willReturn([]);

        $this->phpSqlFacade->setSqlParser($sqlPhpParseMock);
        $this->phpSqlFacade->parse('select 1');

        $this->parserInterfaceMock = $this->createMock(ParserInterface::class);
        $this->parserInterfaceMock->expects($this->any())
            ->method('parse')->willReturn('test');
    }

    public function testCollectionParser() {
        $this->phpSqlFacade->setCollectionParser($this->parserInterfaceMock);

        $this->assertEquals('test', $this->phpSqlFacade->getCollectionName());
    }

    public function testProjectionParser() {
        $this->phpSqlFacade->setProjectionParser($this->parserInterfaceMock);

        $this->assertEquals('test', $this->phpSqlFacade->getProjection());
    }

    public function testQueryParser() {
        $this->phpSqlFacade->setQueryParser($this->parserInterfaceMock);

        $this->assertEquals('test', $this->phpSqlFacade->getQuery());
    }

    public function testSortParser() {
        $this->phpSqlFacade->setSortParser($this->parserInterfaceMock);

        $this->assertEquals('test', $this->phpSqlFacade->getSort());
    }

    public function testLimitParser() {
        $this->phpSqlFacade->setLimitParser($this->parserInterfaceMock);

        $this->assertEquals('test', $this->phpSqlFacade->getLimit());
    }

    public function testSkipParser() {
        $this->phpSqlFacade->setSkipParser($this->parserInterfaceMock);

        $this->assertEquals('test', $this->phpSqlFacade->getSkip());
    }

    /**
     * @expectedException \MongoSql\MongoSqlException
     */
    public function testParseThrowsException() {
        $sqlPhpParseMock = $this->createMock(PHPSQLParser::class);

        $sqlPhpParseMock
            ->expects($this->any())
            ->method('parse')->willReturn(null);

        $this->phpSqlFacade->setSqlParser($sqlPhpParseMock);
        $this->phpSqlFacade->parse('select 1');

        $this->parserInterfaceMock = $this->createMock(ParserInterface::class);
        $this->parserInterfaceMock->expects($this->any())
            ->method('parse')->willReturn('test');

        $this->phpSqlFacade->setSkipParser($this->parserInterfaceMock);
        $this->phpSqlFacade->getSkip();
    }
}