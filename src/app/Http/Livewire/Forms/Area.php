<?php
namespace App\Http\Livewire\Forms;
use App\Interface\DataTypeModel;
use App\Http\Livewire\Forms\Coordinates;

class Area implements DataTypeModel
{
    public function __construct($listCoordinates = [])
    {
        for ($i = 0; $i < count($listCoordinates); $i++) {
            $lat = $listCoordinates[$i]['lat'];
            $long = $listCoordinates[$i]['long'];
            $this->listCoordinates[] = new Coordinates($lat, $long);
        }
    }
    public static function rules($fieldname = null): array
    {
        if ($fieldname == null) {
            return [
                'listCoordinates.*.lat' => 'nullable|numeric',
                'listCoordinates.*.long' => 'nullable|numeric',
            ];
        }
        return [
            $fieldname.'.*.lat' => 'nullable|numeric',
            $fieldname.'.*.long' => 'nullable|numeric',
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
