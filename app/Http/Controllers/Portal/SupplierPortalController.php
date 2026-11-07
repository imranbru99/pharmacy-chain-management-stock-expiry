<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class SupplierPortalController extends Controller
{
    public function index(): View
    {
        return view('portal.supplier.index');
    }

    public function orders(): View
    {
        return view('portal.supplier.orders');
    }
}
