<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class RiderPortalController extends Controller
{
    public function index(): View
    {
        return view('portal.rider.index');
    }

    public function deliveries(): View
    {
        return view('portal.rider.deliveries');
    }
}
