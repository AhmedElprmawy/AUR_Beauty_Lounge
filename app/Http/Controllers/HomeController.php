<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Bridal;
use App\Models\BridalPackage;
use App\Models\Transformation;
use App\Models\Gallery;
use App\Models\Staff;
use App\Models\Testimonial;
use App\Models\Video;


class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('order')->get();
        $bridal = Bridal::where('is_active', true)->first();
        $packages = BridalPackage::where('is_active', true)->orderBy('order')->get();
        $transformations = Transformation::where('is_active', true)->orderBy('order')->get();
        $galleries = Gallery::where('is_active', true)->orderBy('order')->get();
        $staff = Staff::where('is_active', true)->orderBy('order')->get();
        $testimonials = Testimonial::where('is_active', true)->get();
        $videos = Video::where('is_active', true)->orderBy('created_at', 'desc')->get();

        return view('layouts.index', compact(
            'services', 'bridal', 'packages', 'transformations',
            'galleries', 'staff', 'testimonials','videos'
        ));
    }
}