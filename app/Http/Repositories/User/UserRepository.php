<?php

namespace App\Http\Repositories\User;

use App\Exports\UsersExport;
use App\Http\Repositories\User\UserInterface;
use App\Http\Resources\UserResource;
use App\Imports\ImportUser;
use App\Models\User;
use App\Models\UserList;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class UserRepository implements UserInterface
{

    use CommonTrait;

    public function store($request)
    {
        try {
            $users = Http::get('https://61f07509732d93001778ea7d.mockapi.io/api/v1/user/users?page=1&limit=10')->json();
            $data = [];

            foreach ($users as $key => $user) {
                $data[$key]['first_name'] = $user['first_name'];
                $data[$key]['last_name'] = $user['last_name'];
                $data[$key]['address'] = $user['address'];
                $data[$key]['job_title'] = $user['job_title'];
                $data[$key]['created_at'] = Carbon::make($user['createdAt']);
                $data[$key]['updated_at'] = Carbon::now();
            }
            UserList::insert($data);
            $time = (Carbon::now())->addDay()->format('m-d-Y');
            Excel::store(new UsersExport(), '/public/file/users-'.$time.'.csv');
            return $this->sendSuccess("success",true);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }
    }

    public function index()
    {
        try {
            $data = User::all();
            $users = UserResource::collection($data);
            return $this->sendSuccess('User Fetched Successfully', $users);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }

    }

    public function import($request)
    {
        try {

            $fileContent = file(($request->file('file')->getPathname()));
            $data = [];
            foreach ($fileContent as $key => $content) {
                $data[$key]['id'] = str_getcsv($content)[0];
                $data[$key]['name'] = str_getcsv($content)[1];
                $data[$key]['email'] = str_getcsv($content)[2];
            }
            array_shift($data);
            return $this->sendSuccess("file uploaded", $data);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }
    }
}