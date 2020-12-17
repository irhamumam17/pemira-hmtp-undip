<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;

class MahasiswaImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Mahasiswa([
            'nim' => $row[0],
            'name' => $row[1],
            'angkatan' => $row[2],
            'email' => $row[4],
            'hint_password' => Str::random(6),
        ]);
    }

    public function startRow(): int{
        return 2;
    }
}
