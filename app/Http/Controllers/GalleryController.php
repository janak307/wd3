<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class GalleryController extends Controller
{
    public function show() {
     	dd(\Auth::User());
    }
}
