<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //all notifications for the logged in user
    public function index(Request $request)
    {
        return $request->user()->notifications()->cursorPaginate(15);
    }
}
