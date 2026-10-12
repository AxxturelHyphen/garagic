<?php

namespace App\Ai;

use Exception;

/**
 * error controlado del oraculo: la ia no ha podido responder.
 * el resto de la app solo captura esta excepcion y muestra un mensaje amable,
 * asi no tiene que saber si fallo la red, la clave o el formato.
 */
class OracleException extends Exception {}
