<?php

namespace App\Http\Controllers;

use App\Domain\Factories\IRazaFactory;
use Illuminate\Http\JsonResponse;

class RazaTestController extends Controller
{
    public function __construct(
        private IRazaFactory $razaFactory,
    ) {
    }

    public function probar(): JsonResponse
    {
        $brahman = $this->razaFactory->create('Brahman');
        $nelore = $this->razaFactory->create('Nelore');

        return response()->json([
            'razas' => [
                [
                    'nombre' => $brahman->getNombre(),
                    'peso_promedio_adulto_kg' => $brahman->getPesoPromedioAdultoKg(),
                    'origen' => $brahman->getOrigen(),
                    'caracteristicas' => $brahman->caracteristicas(),
                    'factor_crecimiento' => $brahman->factorCrecimiento(),
                ],
                [
                    'nombre' => $nelore->getNombre(),
                    'peso_promedio_adulto_kg' => $nelore->getPesoPromedioAdultoKg(),
                    'origen' => $nelore->getOrigen(),
                    'caracteristicas' => $nelore->caracteristicas(),
                    'factor_crecimiento' => $nelore->factorCrecimiento(),
                ],
            ],
        ]);
    }
}