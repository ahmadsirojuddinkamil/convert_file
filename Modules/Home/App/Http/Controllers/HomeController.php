<?php

namespace Modules\Home\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Comment\App\Models\Comment;

class HomeController extends Controller
{
    public function index()
    {
        $comments = Comment::latest()->get();

        // Log::info('User accessed home page.');
        // Log::emergency('Emergency situation occurred!');
        // Log::alert('Alert situation occurred!');
        // Log::critical('Critical situation occurred!');
        // Log::error('An error occurred!');
        // Log::warning('Warning situation occurred!');
        // Log::notice('Notice situation occurred!');
        // Log::debug('Debug information logged!');

        // $logFile = storage_path('logs/laravel.log');
        // $logContent = file_get_contents($logFile);
        // dd($logContent);

        // $logFile = storage_path('logs/laravel.log');
        // $logContent = file($logFile);

        // $logMethods = ['info', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'debug'];
        // $logDetails = [];

        // foreach ($logContent as $logLine) {
        //     foreach ($logMethods as $method) {
        //         if (strpos($logLine, strtoupper($method)) !== false) {
        //             $logDetails[$method][] = $logLine;
        //         }
        //     }
        // }

        // file_put_contents($logFile, '');

        // dd($logDetails);

        return view('home::layouts.home.index', compact('comments'));
    }
}
