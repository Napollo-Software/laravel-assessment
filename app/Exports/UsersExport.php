<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::orderBy('id', 'desc')->take(30)->get(['id', 'name', 'email']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email'
        ];
    }
}
