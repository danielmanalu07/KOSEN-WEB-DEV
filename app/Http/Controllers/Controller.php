<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getQrCode(?string $code): string
    {
        // Membuat QR Code dan mengubahnya ke base64 format
        $qrcode = "data:image/svg+xml;base64," . base64_encode(QrCode::size(300)->style('round')->generate($code));
        return $qrcode;
    }
}
