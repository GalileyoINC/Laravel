<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class DemoController extends Controller
{
    /**
     * Display Bootstrap WYSIWYG Demo
     */
    public function bootstrapWysiwyg(): View
    {
        return ViewFacade::make('web.demo.bootstrap-wysiwyg');
    }

    /**
     * Display GrapeJS Demo
     */
    public function grape(): View
    {
        return ViewFacade::make('web.demo.grape');
    }

    /**
     * Display GrapeJS Demo 2
     */
    public function grape2(): View
    {
        return ViewFacade::make('web.demo.grape2');
    }
}
