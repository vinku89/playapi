<?php

namespace Contus\Base\Elastic;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class BaseIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $settings = [
        'analysis' => [
            'analyzer' => [
                'search_analyzer' => [
                    'tokenizer' => 'my_tokenizer',
                    'filter' => [
                        'standard',
                        'lowercase',
                    ],
                ],
            ],
            'tokenizer' => [
                'my_tokenizer' => [
                    "type" => "edgeNGram",
                    "min_gram" => 1,
                    "max_gram" => 15,
                    "token_chars" => [
                        "letter",
                        "digit",
                    ],
                ],
            ],
        ],
    ];
}
