<?php

namespace App\Ai;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * cliente http de la ia: solo sabe enviar mensajes y devolver el texto.
 * no sabe nada de vehiculos ni de prompts, eso lo montan otras clases.
 */
class OracleClient
{
    /**
     * enviar los mensajes a la api compatible con openai y devolver el texto de la respuesta.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  bool  $json  pedir a la ia que responda en json valido
     *
     * @throws OracleException si falta la clave, no hay conexion o la api da error
     */
    public function chat(array $messages, bool $json = true): string
    {
        $config = config('services.ai');

        // sin clave no tiene sentido llamar: fallar pronto y con un mensaje claro
        if (empty($config['key'])) {
            throw new OracleException('falta AI_API_KEY en el .env');
        }

        $payload = [
            'model' => $config['model'],
            'temperature' => (float) $config['temperature'],
            'messages' => $messages,
        ];

        // el modo json hace que falle menos el formato, pero la respuesta
        // se valida igualmente despues porque la ia puede equivocarse
        if ($json) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        try {
            $response = Http::withToken($config['key'])
                ->acceptJson()
                ->timeout((int) $config['timeout'])
                ->post(rtrim($config['base_url'], '/').'/chat/completions', $payload);
        } catch (ConnectionException $e) {
            throw new OracleException('no se pudo conectar con la ia', previous: $e);
        }

        if ($response->failed()) {
            // guardar el detalle en el log para depurar, pero no ensenarlo al usuario
            Log::warning('la ia ha respondido con error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new OracleException("la ia ha respondido con error {$response->status()}");
        }

        // el texto generado esta en la primera opcion de la respuesta
        $text = $response->json('choices.0.message.content');

        if (! is_string($text) || $text === '') {
            throw new OracleException('la ia ha devuelto una respuesta vacia');
        }

        return $text;
    }
}
