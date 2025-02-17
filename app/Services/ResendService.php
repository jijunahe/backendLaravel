<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ResendService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.resend.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('RESEND_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
        $this->apiKey = env('RESEND_API_KEY');
    }

    public function sendWelcomeEmail($to, $name)
    {
        try {
            $response = $this->client->post('emails', [
                'json' => [
                    'from' => 'oscardiazovalle@gmail.com',  // Cambia esto por tu dominio autorizado
                    'to' => $to,
                    'subject' => '¡Bienvenido a la empresa!',
                    'html' => "<h1>Hola, $name</h1><p>Bienvenido a nuestra empresa. Esperamos que tengas una gran experiencia con nosotros.</p>",
                ]
            ]);
            Log::error('Enviando correo a : ' .$to. ' Respuesta '.json_encode( $response));
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('Error al enviar el correo: ' . $e->getMessage());
            return ['error' => 'No se pudo enviar el correo'];
        }
    }
}
