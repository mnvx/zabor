<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Excel;

class PrepareGeoJsonDataCommand extends Command
{
    protected $signature = 'geojson:prepare';
    protected $description = 'Read raw GeoJson data and Excel file and combine it into new GeoJson file';

    public function handle()
    {
        $geoJson = json_decode(
            file_get_contents(config('app.geojson.raw_file'))
        );

        /** @var Excel $excel */
        $excel = App::make('excel');
        $excel->load(config('app.geojson.excel_file'), function($reader) use ($geoJson) {

            $resultsByKey = [];
            $results = $reader->get();
            foreach ($results as $result) {
                $numbers = explode(',', $result->number);
                foreach ($numbers as $number)
                {
                    $resultsByKey[trim($number)][] = $result->toArray();
                }
            }

            foreach ($geoJson->features as $feature)
            {
                if ($feature->geometry->type !== 'LineString')
                {
                    continue;
                }
                $feature->geometry->type = 'Polygon';
                $feature->geometry->coordinates = [ $feature->geometry->coordinates ];

                $ref = isset($feature->properties->ref) ? $feature->properties->ref : null;
                if (!empty($resultsByKey[$ref]))
                {
                    $feature->properties->data = view('geojson/popup', [
                        'number' => $resultsByKey[$ref][0]['number'],
                        'data' => $resultsByKey[$ref],
                    ])->render();
                }
            }

            file_put_contents(config('app.geojson.prepared_file'), json_encode($geoJson));
        });
    }
}