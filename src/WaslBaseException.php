<?php
namespace Moltaqa\Wasl;

use Exception;
use Illuminate\Support\Facades\Log;

class WaslBaseException extends Exception
{
    public function report()
    {
        $statusCode = $this->getCode();
        $reasonPhrase = $this->getMessage();
        return response()->json(['error' => "Request failed with status code $statusCode: $reasonPhrase", 'body' => $reasonPhrase], $statusCode);
    }

    public function render($request)
    {

    }
}
