<?php

namespace App\Http\Controllers;

use App\Models\Piter\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::active()->get();
        return view('pages.services.index', compact('services'));
    }

    public function show(string $alias)
    {
        $service = Service::where('alias', $alias)->where('status', 10)->firstOrFail();
        $services = Service::active()->get();
        return view('pages.services.show', compact('service', 'services'));
    }
}
