<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewDashboard');

        $user = Auth::user();

        $data = DashModel::getDashboardStats($user);

        return view('contents.dashboard.Index', $data);
    }
}