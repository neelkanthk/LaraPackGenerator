<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\StreamOutput;

class SiteController extends Controller {

    public function __construct() {
        ;
    }

    public function home() {
        if (view()->exists('pages.home')) {
            $formfields = array(
                'namespace',
                'name',
                'controllers',
                'models',
                'middlewares',
                'requests',
                'events',
                'interfaces',
                'author',
                'description'
            );
            $requiredFormfields = array(
                'namespace',
                'name',
                'author'
            );
            return view('pages.home')->withFields($formfields)->withRequired($requiredFormfields);
        }
    }
    
    public function test()
    {
        
    }

}
