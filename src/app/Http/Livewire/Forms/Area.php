<?php
namespace App\Http\Livewire\Forms;
use App\Interface\DataTypeModel;
use App\Http\Livewire\Forms\Coordinates;

class Area implements DataTypeModel {
    public static function rules($fieldname = null):array{
        if($fieldname == null){
            return [
                '*.*.lat' => 'null|numeric',
                '*.*.long' => 'null|numeric',
            ];
        }
        return [
            '.*.*.lat' => 'numeric',
            '.*.*.long' => 'numeric',
        ];
    }
    public static function decode($previous){
        return json_decode($previous, true);
    }
    public static function encode($value){
        return json_encode($value);
    }
    public function __construct($listCoordinates = []) {
        for($i = 0; $i < count($listCoordinates); $i++){
            for($j = 0; $j < count($listCoordinates[$i]); $j++){
                $lat = $listCoordinates[$i][$j]['lat'] ?? null;
                $long = $listCoordinates[$i][$j]['long'] ?? null;
                $this->{'listCoordinates'.$i}[$j] = new Coordinates($lat, $long);
            }
        }
    }
}
