<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Excel;
use RainLab\User\Models\User;

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

            $users = User::all()->keyBy('email');

            $resultsByKey = [];
            $results = $reader->get();
            foreach ($results as $result) {
                $numbers = explode(',', $result->number);
                $arrayResult = $result->toArray();
                $arrayResult['user'] = $this->getUser(isset($arrayResult['email']) ? $arrayResult['email'] : null, $users);
                foreach ($numbers as $number)
                {
                    $resultsByKey[trim($number)][] = $arrayResult;
                }
            }

            foreach ($geoJson->features as $feature)
            {
                if (!$feature->geometry || !in_array($feature->geometry->type, [
                    'LineString',
                    'Polygon',
                ])) {
                    continue;
                }

                if ($feature->geometry->type !== 'Polygon') {
                    $feature->geometry->coordinates = [ $feature->geometry->coordinates ];
                }
                $feature->geometry->type = 'Polygon';
                if (!$feature->properties) {
                    $feature->properties = new \StdClass();
                }
                $feature->properties->hasContacts = false;

                $ref = isset($feature->properties->ref) ? $feature->properties->ref : null;
                if (!empty($resultsByKey[$ref]))
                {
                    $feature->properties->data = view('geojson/popup', [
                        'number' => $resultsByKey[$ref][0]['number'],
                        'data' => $resultsByKey[$ref],
                    ])->render();

                    foreach ($resultsByKey[$ref] as $item) {
                        if ($item['email'] || $item['phone']) {
                            $feature->properties->hasContacts = true;
                        }

                        $feature->properties->name = (isset($feature->properties->name) ? $feature->properties->name . '|' : '') . $item['name'];
                        $feature->properties->phone = (isset($feature->properties->phone) ? $feature->properties->phone . '|' : '') . $item['phone'];
                        $feature->properties->email = (isset($feature->properties->email) ? $feature->properties->email . '|' : '') . $item['email'];
                    }

                    $feature->properties->stead = $resultsByKey[$ref][0]['number'];
                }
            }

            file_put_contents(config('app.geojson.prepared_file'), json_encode($geoJson));
        });
    }

    protected function getUser($excelEmail, $users)
    {
        if (!trim($excelEmail)) {
            return null;
        }

        foreach (explode(',', $excelEmail) as $email) {
            if (isset($users[trim($email)])) {
                return $users[trim($email)];
            }
        }

        return null;
    }
}