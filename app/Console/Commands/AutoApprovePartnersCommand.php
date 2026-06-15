<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mitra;
use App\Models\PendaftaranPariwisata;

class AutoApprovePartnersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'partners:auto-approve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-approve pending mitra registrations (Faskes & Pariwisata) older than 3 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threeDaysAgo = now()->subDays(3);

        // 1. Auto-approve Faskes (Mitra)
        $pendingMitras = Mitra::pending()
            ->where('is_auto_approve_cancelled', false)
            ->where('created_at', '<=', $threeDaysAgo)
            ->get();

        $mitraCount = 0;
        foreach ($pendingMitras as $mitra) {
            $mitra->update([
                'is_verified'      => true,
                'is_auto_approved' => true,
                'catatan_admin'    => null,
            ]);

            if ($mitra->faskes) {
                $mitra->faskes->update(['status_operasional' => 'open']);
            }
            $mitraCount++;
        }

        // 2. Auto-approve Pariwisata (PendaftaranPariwisata)
        $pendingPariwisata = PendaftaranPariwisata::menunggu()
            ->where('is_auto_approve_cancelled', false)
            ->where('created_at', '<=', $threeDaysAgo)
            ->get();

        $pariwisataCount = 0;
        foreach ($pendingPariwisata as $wisata) {
            $wisata->update([
                'status_review'    => 'disetujui',
                'is_auto_approved' => true,
            ]);
            $pariwisataCount++;
        }

        $this->info("Auto-approve completed: {$mitraCount} Faskes, {$pariwisataCount} Pariwisata approved automatically.");
    }
}

