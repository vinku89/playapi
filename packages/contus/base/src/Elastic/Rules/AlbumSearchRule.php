<?php

namespace Contus\Base\Elastic\Rules;

use ScoutElastic\SearchRule;

class AlbumSearchRule extends SearchRule
{
    public function buildQueryPayload()
    {
        $query = $this->builder->query;
        return [
            "must" => [
                "multi_match" => [
                    "query" => $query,
                    "type" => "phrase_prefix",
                    "fields" => ['album_name^2', 'artist_name'],
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
