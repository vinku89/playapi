<?php

namespace Contus\Base\Elastic\Rules;

use ScoutElastic\SearchRule;

class WebseriesSearchRule extends SearchRule
{
    public function buildQueryPayload()
    {
        $query = $this->builder->query;
        return [
            "must" => [
                "multi_match" => [
                    "query" => $query,
                    "type" => "cross_fields",
                    "analyzer" =>  "standard",
                    "fields" => ['title^3'],
                ],
            ],
            "filter" => [
              "bool" => [
                  "must" => [
                      ["term" => [ "is_active"=> 1 ]],
                    ]
               ]
            ]
        ];
    }
}
