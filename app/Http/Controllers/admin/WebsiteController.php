<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contents.Dashboard');
    }

    public function project()
    {
        return view('contents.Project');
    }

    public function projectstatus()
    {
        return view('contents.ProjectStatus');
    }

    public function task()
    {
        return view('contents.Task');
    }

    public function team()
    {
        return view('contents.Team');
    }

    public function user()
    {
        return view('contents.User');
    }

    public function report()
    {
        return view('contents.Report');
    }
}