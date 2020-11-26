<?php

use Illuminate\Database\Seeder;
use Contus\Video\Models\Countries;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Countries::get()->count() == 0){
            $json = File::get("database/data/countryRegion.json");
            $data = json_decode($json);
            
            foreach ($data as $obj) {
                $country = new Countries();
                $country->code = $obj->countryShortCode;
                $country->name = $obj->countryName;
                $country->is_active = '1';
                $country->creator_id = '1';
                $country->updator_id = '1';
                $country->save();
            }
        } else { echo "Table is not empty, therefore NOT "; }
    }
}
