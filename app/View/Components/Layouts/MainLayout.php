<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainLayout extends Component
{

    public function __construct(
        //Por em todas ás paginas um titulo especifico para ser carregado o component
        public string $pageTitle
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.main-layout');
    }
}
