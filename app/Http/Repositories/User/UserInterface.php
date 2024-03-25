<?php

namespace App\Http\Repositories\User;

interface UserInterface
{
    public function store($request);
    public function index();
    public function import($request);

}