<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;

class ReservationObserver
{
    /**
     * Handle the Reservation "saved" event.
     */
    public function saved(Reservation $reservation)
    {
        $today = Carbon::today();

        // Jika reservasi dikonfirmasi dan hari H-nya sama dengan hari ini → reserve table
        if ($reservation->status === 'confirmed' && $reservation->reservation_date->isToday()) {
            Table::where('id', $reservation->table_id)->update(['status' => 'reserved']);
        } 
        // Jika reservasi dibatalkan, expired, completed, atau lewat hari H → free table
        elseif (in_array($reservation->status, ['cancelled', 'expired', 'completed']) 
                || $reservation->reservation_date->lt($today)) {
            Table::where('id', $reservation->table_id)->update(['status' => 'available']);
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation)
    {
        // Saat reservasi dihapus → meja jadi available
        Table::where('id', $reservation->table_id)->update(['status' => 'available']);
    }
}
