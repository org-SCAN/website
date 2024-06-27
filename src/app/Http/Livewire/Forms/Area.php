<?php
namespace App\Http\Livewire\Forms;
use App\Interface\DataTypeModel;
use App\Http\Livewire\Forms\Coordinates;

class Area implements DataTypeModel
{
    public $divs = [];
    public function __construct($polygons = [])
    {
        for ($i = 0; $i < count($polygons); $i++) {
            for ($j = 0; $j < count($polygons[$i]); $j++) {
                $lat = $polygons[$i][$j]["lat"];
                $long = $polygons[$i][$j]["long"];
                $this->polygons[$i][$j] = new Coordinates($lat, $long);
            }
        }
    }
    public static function rules($fieldname = null): array
    {
        if ($fieldname == null) {
            return [
                "polygons.*.*.lat" => "nullable|numeric",
                "polygons.*.*.long" => "nullable|numeric",
            ];
        }
        return [
            $fieldname.'.polygons.*.*.lat' => 'nullable|numeric',
            $fieldname.'.polygons.*.*.long' => 'nullable|numeric',
        ];
    }

    public static function decode($previous)
    {
        return json_decode($previous, true);
    }

    public static function encode($value)
    {
        return json_encode($value);
    }

}
