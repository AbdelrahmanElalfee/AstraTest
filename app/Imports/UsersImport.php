<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Model|User|null
     */
    public function model(array $row): Model|User|null
    {
        return new User([
            'full_name'     => $row[0],
            'email'    => $row[1],
            'phone_number' => $row[2]
        ]);
    }
}
