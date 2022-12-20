<?php
namespace App\Interface;

interface DataTypeModel{
    public static function rules() : array;
    public static function previous($value);
}
