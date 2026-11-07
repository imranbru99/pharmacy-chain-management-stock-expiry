<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CustomerPortalController extends Controller
{
    public function index(): View
    {
        return view('portal.customer.index');
    }

    public function orders(): View
    {
        return view('portal.customer.orders');
    }
}
