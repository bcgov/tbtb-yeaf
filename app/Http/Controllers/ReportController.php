<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class ReportController extends Controller
{
    public function students(Request $request) {
        return Response::json(\App\Models\Student::get());
    }
    public function studentsWithGrants(Request $request) {
        return Response::json(\App\Models\Student::with('grants.grantIneligibles', 'comments', 'grants.appeals', 'grants.py')->get());
    }
    public function staff(Request $request) {
        return Response::json(\App\Models\User::get());
    }
    public function grants(Request $request) {
        return Response::json(\App\Models\Grant::get());
    }
    public function grantIneligible(Request $request) {
        return Response::json(\App\Models\GrantIneligible::get());
    }
    public function comments(Request $request) {
        return Response::json(\App\Models\Comment::get());
    }
    public function appeals(Request $request) {
        return Response::json(\App\Models\Appeal::get());
    }

}
