<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class BienvenidoEmpleado
{
    public static function enviarCorreo($empleado)
    {
        $data = [
            'nombres' => $empleado->nombres,
            'email' => $empleado->email
        ];

        Mail::send('emails.bienvenida', $data, function ($message) use ($empleado) {
            $message->to($empleado->email, $empleado->nombres)
                    ->subject('¡Bienvenido a la empresa!')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
    }
}
