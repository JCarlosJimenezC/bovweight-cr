<?php

namespace App\Domain\Exceptions;

use RuntimeException;

class ServicioYolov8NoDisponibleException extends RuntimeException
{
    public function __construct(string $message = 'El servicio YOLOv8 no está disponible')
    {
        parent::__construct($message);
    }
}