<?php

namespace Tests\Unit;

use App\Domain\Observers\IRegistroPesoObserver;
use App\Domain\Observers\RegistroPesoSubject;
use App\Models\RegistroPeso;
use Tests\TestCase;

class RegistroPesoSubjectTest extends TestCase
{
    public function test_notificar_llama_a_todos_los_observadores_suscritos(): void
    {
        $subject = new RegistroPesoSubject();
        $registro = new RegistroPeso([
            'animal_id' => 1,
            'peso_kg' => 480,
            'confianza_porcentaje' => 92,
            'metodo_usado' => 'yolov8',
            'fecha_registro' => '2026-05-15',
        ]);

        $observerUno = $this->createMock(IRegistroPesoObserver::class);
        $observerDos = $this->createMock(IRegistroPesoObserver::class);
        $observerTres = $this->createMock(IRegistroPesoObserver::class);

        $observerUno->expects($this->once())
            ->method('onPesoRegistrado')
            ->with($registro);

        $observerDos->expects($this->once())
            ->method('onPesoRegistrado')
            ->with($registro);

        $observerTres->expects($this->once())
            ->method('onPesoRegistrado')
            ->with($registro);

        $subject->suscribir($observerUno);
        $subject->suscribir($observerDos);
        $subject->suscribir($observerTres);

        $subject->notificar($registro);
    }
}