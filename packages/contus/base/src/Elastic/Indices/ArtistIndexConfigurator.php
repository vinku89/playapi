<?php

namespace Contus\Base\Elastic\Indices;

use Contus\Base\Elastic\BaseIndexConfigurator;

class ArtistIndexConfigurator extends BaseIndexConfigurator
{
   protected $name;
   public function __construct(){
      $indexName = (!empty(config('contus.audio.audioElasticsearchIndexPrefix.elasticsearch_index_prefix')))
                  ?config('contus.audio.audioElasticsearchIndexPrefix.elasticsearch_index_prefix').'artists'
                  :'artists';
      $this->name = $indexName;
   }
}