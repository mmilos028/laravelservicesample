<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view(
            'index'
        );
    }

}