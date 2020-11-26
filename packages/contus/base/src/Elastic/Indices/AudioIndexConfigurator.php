<?php

namespace Contus\Base\Elastic\Indices;

use Contus\Base\Elastic\BaseIndexConfigurator;

class AudioIndexConfigurator extends BaseIndexConfigurator
{
   protected $name;
   public function __construct(){
      $indexName = (!empty(config('contus.audio.audioElasticsearchIndexPrefix.elasticsearch_index_prefix')))
                  ?config('contus.audio.audioElasticsearchIndexPrefix.elasticsearch_index_prefix').'audios'
                  :'audios';
      $this->name = $indexName;
   }
}