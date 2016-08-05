# README #

### Purpose of the code ###

This repository contains SQL compatible Mongodb client.

Only SELECT clause is available.

### Requirements ###

1. PHP >= 5.6.0
2. mongodb >= 3.0.0

### Setup ###

1. Clone this repository
2. Create params.php in ./configs folder based on params.php.dist with right DB parameters
4. Run composer install in the root folder, to install all needed dependencies.
5. mongodb extension is required. Composer will fail to install dependencies without it,
6. All test are in ./tests folder. For running tests you should use phpunit (https://phpunit.de)
> php vendo/bin/phpunit

### Usage ###

To run the client use:
> php ./bin/console mongo:execute

SQL structure:
> [SELECT **Projection**]

> [FROM **Target**]

> [WHERE **Condition** *]

> [ORDER BY **Fields*** **[ASC| DESC]*** *]

> [SKIP **SkipRecords** *]

> [LIMIT **MaxRecords** *]

### Supported operators ###

#### Comparison operators ####

* '='
* '<>'
* '>'
* '<'
* '>='
* '<='

#### Logical operators ####

* AND
* OR
* XOR - MongoDB does not support XOR so it was implemented as **(A AND B) NOR (A NOR B)**

Operator precedence is taken into account so **AND** has higher priority then **OR** and so on.

### CI ###

Code is being tested automatically in both PHP 5.6 and PHP 7.0 using GitLab CI. Check [Pipelines](https://git.zophiatech.com/ozanichkovsky/mongodb-sql-syntax-cli/pipelines) to see builds

Some builds are faild there because it was the first time because of some problems with configuration.

### Code ###

To make code testable it is decoupled as much as possible. So Dependency Injection is used to instantiate and set all dependencies. Check *app/services.php*