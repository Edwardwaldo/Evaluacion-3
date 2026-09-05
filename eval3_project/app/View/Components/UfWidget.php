<?php

namespace App\View\Components;

use App\Services\UfService;
use Illuminate\View\Component;

class UfWidget extends Component
{
    public array $uf;

    public function __construct(UfService $ufService)
    {
        $this->uf = $ufService->obtenerValorUf();
    }

    public function render()
    {
        return view('components.uf-widget');
    }
}
