<?php

namespace Contus\Base\Elastic\Rules;

use ScoutElastic\SearchRule;

class AudioSearchRule extends SearchRule
{
    public function buildQueryPayload()
    {
        $query = $this->builder->query;
        return [
            "must" => [
                "multi_match" => [
                    "query" => $query,
                    "type" => "phrase_prefix",
                    "fields" => ['audio_title^2', 'artist_name', 'album_name'],
                ],
            ],
            "filter" => [
              "bool" => [
                  "must" => [
                      ["term" => [ "is_active"=> 1 ]],
                      ["term" => [ "is_archived"=> 0 ]],
                      ["term" => [ "job_status" => "complete"]]
                  ]
               ]
            ]
        ];
    }
}
