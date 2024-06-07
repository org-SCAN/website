<?php
namespace App\Http\Livewire\Forms;
use App\Interface\DataTypeModel;
use App\Http\Livewire\Forms\Coordinates;

class Area implements DataTypeModel {
    public static function rules($fieldname = null):array{
        if($fieldname == null){
            return [
                'area' => 'array',
                'area.*.lat' => 'numeric',
                'area.*.long' => 'numeric',
            ];
        }
        return [
            $fieldname.'.*.lat' => 'numeric',
            $fieldname.'.*.long' => 'numeric',
        ];
    }
    public static function decode($previous){
        return json_decode($previous, true);
    }
    public static function encode($value){
        return json_encode($value);
    }
    public function __construct($lat1, $long1, $lat2, $long2, $lat3, $long3, $lat4, $long4) {
        $this->lat1 = $lat1;
        $this->long1 = $long1;
        $this->lat2 = $lat2;
        $this->long2 = $long2;
        $this->lat3 = $lat3;
        $this->long3 = $long3;
        $this->lat4 = $lat4;
        $this->long4 = $long4;
    }
}
