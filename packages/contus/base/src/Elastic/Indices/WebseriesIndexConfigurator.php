<?php

namespace Contus\Base\Elastic\Indices;

use Contus\Base\Elastic\BaseIndexConfigurator;

class WebseriesIndexConfigurator extends BaseIndexConfigurator
{
    protected $name;
    public function __construct(){
        $indexName = (!empty(config('contus.audio.audioElasticsearchIndexPrefix.elasticsearch_index_prefix')))
        ?config('contus.audio.audioElasticsearchIndexPrefix.elasticsearch_index_prefix').'video_webseries_detail'
        :'video_webseries_detail';
        $this->name = $indexName;
     }
}
