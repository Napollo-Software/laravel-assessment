<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return UserList::orderBy('id', 'desc')->take(10)->get(['id', 'first_name','last_name','job_title', 'address']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Job Title',
            'Address'
        ];
    }
}
