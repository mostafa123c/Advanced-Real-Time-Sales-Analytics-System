<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index($page = 'analytics')
    {
        $validPages = ['analytics', 'recommendations', 'pricing', 'add-order'];
        $page = in_array($page, $validPages) ? $page : 'analytics';

        $viewData = [];

        if ($page === 'add-order') {
            $viewData['products'] = DB::table('products')->get();
        }

        return view("dashboard.$page", $viewData);
    }
}
