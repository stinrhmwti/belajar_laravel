<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception untuk kesalahan permanen pada pengiriman WhatsApp
 * (misal: nomor tidak valid, token salah, HTTP 400/401/404)
 * yang TIDAK perlu dicoba ulang (retry) oleh Queue Job.
 */
class PermanentWhatsappException extends Exception
{
}
