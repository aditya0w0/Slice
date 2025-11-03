<?php

namespace App\Http\Controllers;

class navBar extends Controller
{
    public function getNavbar()
    {
        $logoPath = public_path("images/logo.svg");
        $logoContent = file_get_contents($logoPath);
        $navBar = ["logo" => $logoContent, "Home" => "/", "About" => "/about", "Services" => "/services", "Contact" => "/contact"];
        return view('Home', compact('navBar'));
    }
}
