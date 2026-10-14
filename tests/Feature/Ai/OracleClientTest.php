<?php

use App\Ai\OracleClient;
use App\Ai\OracleException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

const MENSAJES = [
    ['role' => 'system', 'content' => 'eres el oraculo'],
    ['role' => 'user', 'content' => 'me tiembla el volante al frenar'],
];

beforeEach(function () {
    config([
        'services.ai.key' => 'clave-de-prueba',
        'services.ai.base_url' => 'https://ia.test/v1',
        'services.ai.model' => 'modelo-de-prueba',
    ]);

    // cualquier peticion sin fake falla en vez de salir a internet
    Http::preventStrayRequests();
});

test('sends the messages to the configured endpoint and returns the generated text', function () {
    Http::fake([
        'ia.test/v1/chat/completions' => Http::response([
            'choices' => [['message' => ['content' => '{"urgencia":"alta"}']]],
        ]),
    ]);

    $text = (new OracleClient)->chat(MENSAJES);

    expect($text)->toBe('{"urgencia":"alta"}');

    Http::assertSent(fn (Request $request) => $request->hasHeader('Authorization', 'Bearer clave-de-prueba')
        && $request['model'] === 'modelo-de-prueba'
        && $request['messages'] === MENSAJES
        && $request['response_format'] === ['type' => 'json_object']);
});

test('does not ask for json mode when plain text is requested', function () {
    Http::fake([
        'ia.test/v1/chat/completions' => Http::response([
            'choices' => [['message' => ['content' => 'hola']]],
        ]),
    ]);

    (new OracleClient)->chat(MENSAJES, json: false);

    Http::assertSent(fn (Request $request) => ! isset($request['response_format']));
});

test('throws without calling the api when the key is missing', function () {
    config(['services.ai.key' => null]);
    Http::fake(['ia.test/v1/chat/completions' => Http::response()]);

    expect(fn () => (new OracleClient)->chat(MENSAJES))
        ->toThrow(OracleException::class, 'falta AI_API_KEY');

    Http::assertNothingSent();
});

test('throws when the api answers with an error status', function () {
    Http::fake(['ia.test/v1/chat/completions' => Http::response(['error' => 'limite'], 429)]);

    expect(fn () => (new OracleClient)->chat(MENSAJES))
        ->toThrow(OracleException::class, 'error 429');
});

test('throws when the connection fails', function () {
    Http::fake(['ia.test/v1/chat/completions' => fn () => throw new ConnectionException('timeout')]);

    expect(fn () => (new OracleClient)->chat(MENSAJES))
        ->toThrow(OracleException::class, 'no se pudo conectar');
});

test('throws when the api returns no text', function () {
    Http::fake(['ia.test/v1/chat/completions' => Http::response(['choices' => []])]);

    expect(fn () => (new OracleClient)->chat(MENSAJES))
        ->toThrow(OracleException::class, 'respuesta vacia');
});
