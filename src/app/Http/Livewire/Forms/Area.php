<?php
namespace App\Http\Livewire\Forms;
use App\Interface\DataTypeModel;
use App\Http\Livewire\Forms\Coordinates;

class Area implements DataTypeModel {
    public static function rules($fieldname = null):array{
        if($fieldname == null){
            return [
                '*.*.lat' => 'nullable|numeric',
                '*.*.long' => 'nullable|numeric',
            ];
        }
        return [
            $fieldname.'.*.*.lat' => 'nullable|numeric',
            $fieldname.'.*.*.long' => 'nullable|numeric',
        ];
    }
    public static function decode($previous){
        return json_decode($previous, true);
    }
    public static function encode($value){
        return json_encode($value);
    }
    public function __construct($listCoordinates = []) {
        for($i = 1; $i <= count($listCoordinates); $i++){
            for($j = 1; $j < count($listCoordinates[$i]); $j++){
                $lat = $listCoordinates[$i][$j]['lat'] ?? null;
                $long = $listCoordinates[$i][$j]['long'] ?? null;
                $this->{$i}[$j] = new Coordinates($lat, $long);
            }
        }
    }
}
