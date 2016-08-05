<?php
/**
 * Created by PhpStorm.
 * User: sasha
 * Date: 04.08.2016
 * Time: 0:41
 */

namespace MongoSql\Parser;


interface ParserFacadeInterface {

    function parse($sql);

    function getCollectionName();

    function getProjection();

    function getQuery();

    function getSort();

    function getSkip();

    function getLimit();
}