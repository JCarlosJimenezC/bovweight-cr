<?php

namespace App\Http\Controllers;

use App\Domain\Factories\IRazaFactory;
use App\Domain\Repositories\IAnimalRepository;
use App\Models\Animal;
use App\Models\Rancho;
use App\Services\ReporteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnimalSeederController extends Controller
{
    public function __construct(
        private IRazaFactory $razaFactory,
        private IAnimalRepository $animalRepository,
    ) {
    }

    public function sembrar(): JsonResponse
    {
        $resultado = DB::transaction(function (): array {
            $rancho = Rancho::create([
                'nombre' => 'Rancho BovWeight CR',
                'ubicacion' => 'San Carlos, Alajuela',
                'propietario' => 'IF7100 UCR',
            ]);

            $datosAnimales = [
                [
                    'arete' => sprintf('BWCR-%d-001', $rancho->id),
                    'raza' => 'Brahman',
                    'sexo' => 'M',
                    'fecha_nacimiento' => '2022-03-15',
                    'peso_kg' => 420.50,
                    'confianza_porcentaje' => 94.20,
                    'metodo_usado' => 'estimacion_visual',
                    'fecha_registro' => '2026-05-10',
                ],
                [
                    'arete' => sprintf('BWCR-%d-002', $rancho->id),
                    'raza' => 'Nelore',
                    'sexo' => 'H',
                    'fecha_nacimiento' => '2021-11-02',
                    'peso_kg' => 398.75,
                    'confianza_porcentaje' => 92.10,
                    'metodo_usado' => 'cinta_barimetrica',
                    'fecha_registro' => '2026-05-11',
                ],
                [
                    'arete' => sprintf('BWCR-%d-003', $rancho->id),
                    'raza' => 'Brahman',
                    'sexo' => 'H',
                    'fecha_nacimiento' => '2023-01-20',
                    'peso_kg' => 365.00,
                    'confianza_porcentaje' => 90.50,
                    'metodo_usado' => 'estimacion_visual',
                    'fecha_registro' => '2026-05-12',
                ],
            ];

            foreach ($datosAnimales as $datosAnimal) {
                $raza = $this->razaFactory->create($datosAnimal['raza']);

                $animal = new Animal([
                    'arete' => $datosAnimal['arete'],
                    'rancho_id' => $rancho->id,
                    'raza' => $raza->getNombre(),
                    'sexo' => $datosAnimal['sexo'],
                    'fecha_nacimiento' => $datosAnimal['fecha_nacimiento'],
                ]);

                $this->animalRepository->save($animal);

                $animal->registrosPeso()->create([
                    'peso_kg' => $datosAnimal['peso_kg'],
                    'confianza_porcentaje' => $datosAnimal['confianza_porcentaje'],
                    'metodo_usado' => $datosAnimal['metodo_usado'],
                    'fecha_registro' => $datosAnimal['fecha_registro'],
                ]);
            }

            return [
                'mensaje' => 'Datos de prueba creados correctamente.',
                'rancho_id' => $rancho->id,
                'animales_creados' => count($datosAnimales),
            ];
        });

        return response()->json($resultado, 201);
    }

    public function ver(int $ranchoId, ReporteService $reporteService): JsonResponse
    {
        return response()->json($reporteService->generarReporteRancho($ranchoId));
    }
}