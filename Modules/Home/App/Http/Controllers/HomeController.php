<?php

namespace Modules\Home\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Comment\App\Models\Comment;

class HomeController extends Controller
{
    public function index()
    {
        $comments = Comment::latest()->get();

        return view('home::layouts.home.index', compact('comments'));
    }
}
