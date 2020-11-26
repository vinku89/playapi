<?php

namespace App\Console\Commands;

use Artisan;
use Contus\Base\Model;
use Illuminate\Console\Command;

class SetupElasticMapping extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contus:update-mapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update type mapping';

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
        $baseModel = new Model();
        $elasticModel = $baseModel->getElasticModel();

        foreach ($elasticModel as $class) {
            Artisan::call('elastic:update-mapping', ["model" => 'Contus\\Video\\Models\\' . $class]);
            $this->info(Artisan::output());
        }
    }
}
