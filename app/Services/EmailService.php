<?php

namespace App\Services;

use App\Services\BienvenidoEmpleado;

class EmailService
{
    public function enviarCorreoBienvenida($empleado)
    {
        BienvenidoEmpleado::enviarCorreo($empleado);
    }
}
