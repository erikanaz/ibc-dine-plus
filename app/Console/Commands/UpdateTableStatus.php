<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;

class UpdateTableStatus extends Command
{
    protected $signature = 'tables:update-status';
    protected $description = 'Update status meja berdasarkan reservasi yang sudah lewat atau dibatalkan';

    public function handle()
    {
        $today = Carbon::today();

        // Ambil ID meja dari reservasi yang lewat hari H atau dibatalkan/completed/expired
        $reservationTableIds = Reservation::where(function($q) use ($today) {
            $q->where('reservation_date', '<', $today)
              ->orWhereIn('status', ['cancelled', 'completed', 'expired']);
        })->pluck('table_id');

        // Update hanya meja yang statusnya reserved
        Table::whereIn('id', $reservationTableIds)
             ->where('status', 'reserved')
             ->update(['status' => 'available']);

        $this->info('Status meja berhasil diperbarui.');
    }
}
