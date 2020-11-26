<?php

namespace Contus\Base\Elastic\Rules;

use ScoutElastic\SearchRule;

class ArtistSearchRule extends SearchRule
{
    public function buildQueryPayload()
    {
        $query = $this->builder->query;
        return [
            "must" => [
                "multi_match" => [
                    "query" => $query,
                    "type" => "phrase_prefix",
                    "fields" => ['artist_name^2'],
                ],
            ],
            "filter" => [
              "bool" => [
                  "must" => [
                      ["term" => [ "is_active"=> 1 ]]
                  ]
               ]
            ]
        ];
    }
}
