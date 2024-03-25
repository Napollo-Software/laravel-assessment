<?php

namespace App\Http\Repositories\User;

use App\Exports\UsersExport;
use App\Http\Repositories\User\UserInterface;
use App\Http\Resources\UserResource;
use App\Imports\ImportUser;
use App\Models\User;
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
            $users = Http::get('https://dummyjson.com/users')->json();
            $data = [];
            foreach ($users["users"] as $key => $user) {
                $data[$key]['name'] = $user['firstName'] . ' ' . $user['lastName'];
                $data[$key]['email'] = $user['email'];
                $data[$key]['password'] = Hash::make($user['password']);
                $data[$key]['created_at'] = Carbon::now();
                $data[$key]['updated_at'] = Carbon::now();
            }
            User::insert($data);
            return Excel::download(new UsersExport(), 'users.csv');
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