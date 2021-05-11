<?php

namespace App\Imports;

use App\Models\Refugee;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RefugeeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Refugee([
            'full_name' => $row[0],
            'date' => $row[1],
            'unique_id' => $row[2],
            'nationality' => $row[3],
            'alias' => $row[4],
            'other_names' => $row[5],
            'mothers_names' => $row[6],
            'fathers_names' => $row[7],
            'role' => $row[8],
            'age' => $row[9],
            'birth_date' => $row[10],
            'date_last_seen' => $row[11],
            'birth_place' => $row[12],
            'gender' => $row[13],
            'passport_number' => $row[14],
            'embarkation_date' => $row[15],
            'detention_place' => $row[16],
            'embarkation_place' => $row[17],
            'destination' => $row[18],
            'route' => $row[19],
            'residence' => $row[20],
        ]);
    }
}
