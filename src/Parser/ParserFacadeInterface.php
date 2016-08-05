<?php
namespace MongoSql\Parser;

/**
 * Interface ParserFacadeInterface
 * @package MongoSql\Parser
 */
interface ParserFacadeInterface {

    /**
     * Parse SQL
     *
     * @param $sql
     */
    function parse($sql);

    /**
     * Get collection name
     *
     * @return string
     */
    function getCollectionName();

    /**
     * Get projection array
     *
     * @return array
     */
    function getProjection();

    /**
     * Get query or null if WHERE clause is not provided
     *
     * @return array|null
     */
    function getQuery();

    /**
     * Get sort array or null if ORDER BY is not provided
     *
     * @return array|null
     */
    function getSort();

    /**
     * Get number of records to skip or null if SKIP is not provided
     *
     * @return int|null
     */
    function getSkip();

    /**
     * Get limit of number of records or null if LIMIT is not provided
     *
     * @return int|null
     */
    function getLimit();
}