<?php
namespace Moltaqa\Wasl;

use Exception;
use Illuminate\Support\Facades\Log;

class WaslBaseException extends Exception
{
    public function report()
    {
        Log::error('WaslMoltaqaException: ' . $this->getMessage());
    }

    public function render($request)
    {
    }
}