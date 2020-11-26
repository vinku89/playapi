<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class SetupElasticIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contus:elastic-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all elastic search Indices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $indicesClass = getClassesInNamespace("Contus\Base\Elastic\Indices");
        $elasticClasses = array_map(function ($class) {
            return str_replace('\\', '\\\\', $class);
        }, $indicesClass);

        foreach ($elasticClasses as $class) {
            $count = strripos($class, "\\");
            $className = substr($class, $count + 1);
            Artisan::call('elastic:create-index', ["index-configurator" => 'Contus\\Base\\Elastic\\Indices\\' . $className]);
            $this->info(Artisan::output());
        }
    }
}
