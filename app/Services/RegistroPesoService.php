<?php

namespace App\Services;

use App\Domain\Observers\AlertaSMS;
use App\Domain\Observers\NotificadorPropietario;
use App\Domain\Observers\RecalculadorICC;
use App\Domain\Observers\RegistroPesoSubject;
use App\Domain\Observers\WebhookSenasa;
use App\Models\RegistroPeso;

class RegistroPesoService
{
    public function __construct(
        private RegistroPesoSubject $subject,
    ) {
        $this->subject->suscribir(new NotificadorPropietario());
        $this->subject->suscribir(new RecalculadorICC());
        $this->subject->suscribir(new WebhookSenasa());
        $this->subject->suscribir(new AlertaSMS());
    }

    public function registrar(array $datos): RegistroPeso
    {
        $registro = RegistroPeso::create($datos);
        $registro->load('animal');

        $this->subject->notificar($registro);

        return $registro;
    }
}