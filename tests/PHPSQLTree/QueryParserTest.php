<?php

namespace MongoSql\Tests\SqlTree;

use MongoSql\Tests\Base;
use MongoSql\PHPSQLTree\QueryParser;
use Symfony\Component\Console\Question\Question;

class QueryParserTest extends Base {

    public function testParseSimpleEquality() {
        $tree = [
            'WHERE' =>
            [
                [
                    'expr_type' => 'colref',
                    'base_expr' => 'a'
                ],
                [
                    'expr_type' => 'operator',
                    'base_expr' => '='
                ],
                [
                    'expr_type' => 'const',
                    'base_expr' => 1
                ]
            ]
        ];
        $parser = new QueryParser();
        $result = $parser->parse($tree);

        $expected = [
            'a' => ['$eq' => 1]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testParseOr() {
        $tree = [
            'WHERE' =>
                [
                    [
                        'expr_type' => 'colref',
                        'base_expr' => 'a'
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => '<'
                    ],
                    [
                        'expr_type' => 'const',
                        'base_expr' => 2
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => 'or'
                    ],
                    [
                        'expr_type' => 'colref',
                        'base_expr' => 'b'
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => '>'
                    ],
                    [
                        'expr_type' => 'const',
                        'base_expr' => 2.1
                    ]
                ]
        ];
        $parser = new QueryParser();
        $result = $parser->parse($tree);

        $expected = [
            '$or' => [
                ['a' => ['$lt' => 2]],
                ['b' => ['$gt' => 2.1]],
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testParseAnd() {
        $tree = [
            'WHERE' =>
                [
                    [
                        'expr_type' => 'colref',
                        'base_expr' => 'a'
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => '<'
                    ],
                    [
                        'expr_type' => 'const',
                        'base_expr' => 2
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => 'and'
                    ],
                    [
                        'expr_type' => 'colref',
                        'base_expr' => 'b'
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => '='
                    ],
                    [
                        'expr_type' => 'const',
                        'base_expr' => '\'10\''
                    ]

                ]
        ];
        $parser = new QueryParser();
        $result = $parser->parse($tree);

        $expected = [
            '$and' => [
                ['a' => ['$lt' => 2]],
                ['b' => ['$eq' => '10']],
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testParseXor() {
        $tree = [
            'WHERE' =>
                [
                    [
                        'expr_type' => 'colref',
                        'base_expr' => 'a'
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => '<'
                    ],
                    [
                        'expr_type' => 'const',
                        'base_expr' => 2
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => 'xor'
                    ],
                    [
                        'expr_type' => 'colref',
                        'base_expr' => 'a'
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => '>'
                    ],
                    [
                        'expr_type' => 'const',
                        'base_expr' => 10
                    ]

                ]
        ];
        $parser = new QueryParser();
        $result = $parser->parse($tree);

        $expected = [
            '$nor' =>
            [
                [
                    '$and' => [
                        ['a' => ['$lt' => 2]],
                        ['a' => ['$gt' => 10]],
                    ]
                ],
                [
                    '$nor' => [
                        ['a' => ['$lt' => 2]],
                        ['a' => ['$gt' => 10]],
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testParseWithBrackets() {
        $tree = [
            'WHERE' =>
                [
                    [
                        'expr_type' => 'colref',
                        'base_expr' => 'a'
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => '<'
                    ],
                    [
                        'expr_type' => 'const',
                        'base_expr' => 2
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => 'AND'
                    ],
                    [
                        'expr_type' => 'bracket_expression',
                        'sub_tree' =>
                        [
                            [
                                'expr_type' => 'colref',
                                'base_expr' => 'a'
                            ],
                            [
                                'expr_type' => 'operator',
                                'base_expr' => '>='
                            ],
                            [
                                'expr_type' => 'const',
                                'base_expr' => 10
                            ],
                            [
                                'expr_type' => 'operator',
                                'base_expr' => 'or'
                            ],
                            [
                                'expr_type' => 'colref',
                                'base_expr' => 'b'
                            ],
                            [
                                'expr_type' => 'operator',
                                'base_expr' => '<>'
                            ],
                            [
                                'expr_type' => 'const',
                                'base_expr' => 10
                            ]
                        ]
                    ],
                ]
        ];
        $parser = new QueryParser();
        $result = $parser->parse($tree);

        $expected = [
            '$and' => [
                ['a' => ['$lt' => 2]],
                [
                    '$or' => [
                        ['a' => ['$gte' => 10]],
                        ['b' => ['$ne' => 10]],
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function testParseEmpty() {
        $empty = [];
        $parser = new QueryParser();
        $result = $parser->parse($empty);

        $this->assertEquals([], $result);
    }
}