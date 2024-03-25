<?php

namespace App\Http\Controllers;

use App\Http\Repositories\User\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    public function store(Request $request)
    {
        return $this->userInterface->store($request);
    }

    public function index()
    {
        return $this->userInterface->index();
    }

    public function import(Request $request)
    {
        return $this->userInterface->import($request);
    }


}
