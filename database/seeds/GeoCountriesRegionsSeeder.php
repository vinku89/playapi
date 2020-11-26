<?php

use Illuminate\Database\Seeder;
use Contus\Video\Models\GeoCountries;
use Contus\Video\Models\GeoRegions;

class GeoCountriesRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(GeoCountries::get()->count() == 0){
            $json = File::get("database/data/countryRegion.json");
            $data = json_decode($json);
            
            foreach ($data as $obj) {
                $country = new GeoCountries();
                $country->country_name = $obj->countryName;
                $country->short_code = $obj->countryShortCode;
                $country->is_active = '1';
                $country->save();
                foreach ($obj->regions as $regionData) {
                    $region = new GeoRegions();
                    $region->region_name = $regionData->name;
                    $region->short_code = (array_key_exists('shortCode', $regionData)) ? $regionData->shortCode : '';
                    $region->country_id = $country->id;
                    $region->is_active = '1';
                    $region->save();
                }

            }
        } else { echo "Table is not empty, therefore NOT "; }
    }
}
