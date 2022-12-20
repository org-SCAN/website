<?php
namespace App\Interface;

interface DataTypeModel{
    public static function rules($fieldname) : array;
    public static function encode($value);
    public static function decode($value);
}
