<?php

namespace App\Support;

use App\Models\ScreeningSession;

final class TicketNumberGenerator
{
    /**
     * Nomor antrean format XXX-XXX (huruf kapital + angka).
     */
    public static function make(): string
    {
        $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $left = '';
            $right = '';
            for ($i = 0; $i < 3; $i++) {
                $left .= $pool[random_int(0, strlen($pool) - 1)];
                $right .= $pool[random_int(0, strlen($pool) - 1)];
            }
            $ticket = $left.'-'.$right;
        } while (ScreeningSession::query()->where('ticket_number', $ticket)->exists());

        return $ticket;
    }
}
