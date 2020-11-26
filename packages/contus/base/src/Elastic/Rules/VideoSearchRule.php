<?php

namespace Contus\Base\Elastic\Rules;

use ScoutElastic\SearchRule;

class VideoSearchRule extends SearchRule
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
                    "fields" => ['title^10'],
                ],
            ],
            "filter" => [
              "bool" => [
                  "must" => [
                      ["term" => [ "is_adult"=> 0 ]],
                      ["term" => [ "is_active"=> 1 ]],
                      ["term" => [ "is_archived"=> 0 ]],
                      ["term" => [ "job_status" => "complete"]]
                  ]
               ]
            ]
        ];
    }
}
