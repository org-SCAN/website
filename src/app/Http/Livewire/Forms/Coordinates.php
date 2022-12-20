<?php

namespace App\Http\Livewire\Forms;
use App\Interface\DataTypeModel;

class Coordinates implements DataTypeModel {
    public function __construct($lat, $long) {
        $this->lat = $lat;
        $this->long = $long;
    }
    public function __toString() {
        return $this->lat . "," . $this->long;
    }
    public function __get($name) {
        if ($name == "lat") {
            return $this->lat;
        }
        if ($name == "long") {
            return $this->long;
        }
        return null;
    }
    public function __set($name, $value) {
        if ($name == "lat") {
            $this->lat = $value;
        }
        if ($name == "long") {
            $this->long = $value;
        }
    }
    public function __isset($name) {
        if ($name == "lat") {
            return isset($this->lat);
        }
        if ($name == "long") {
            return isset($this->long);
        }
        return false;
    }
    public function __unset($name) {
        if ($name == "lat") {
            unset($this->lat);
        }
        if ($name == "long") {
            unset($this->long);
        }
    }
    public function __call($name, $arguments) {
        if ($name == "getLat") {
            return $this->lat;
        }
        if ($name == "getLong") {
            return $this->long;
        }
        if ($name == "setLat") {
            $this->lat = $arguments[0];
        }
        if ($name == "setLong") {
            $this->long = $arguments[0];
        }
    }
    public static function __callStatic($name, $arguments) {
        if ($name == "fromString") {
            $parts = explode(",", $arguments[0]);
            return new Coordinates($parts[0], $parts[1]);
        }
    }
    public function __invoke($lat, $long) {
        $this->lat = $lat;
        $this->long = $long;
    }
    public function __debugInfo() {
        return [
            "lat" => $this->lat,
            "long" => $this->long
        ];
    }
    public function __serialize() {
        return [
            "lat" => $this->lat,
            "long" => $this->long
        ];
    }
    public function __unserialize($data) {
        $this->lat = $data["lat"];
        $this->long = $data["long"];
    }
    public static function rules() : array{
        return [
            'lat' => 'numeric',
            'long' => 'numeric',
        ];
    }
    public static function previous($previous){
        return json_decode($previous, true);
    }
}
