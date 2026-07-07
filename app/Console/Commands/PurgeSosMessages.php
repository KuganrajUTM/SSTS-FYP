<?php

namespace App\Console\Commands;

use App\Models\SosMessage;
use Illuminate\Console\Command;

class PurgeSosMessages extends Command
{
    protected $signature   = 'sos:purge';
    protected $description = 'Delete SOS messages older than 24 hours';

    public function handle(): void
    {
        $expired = SosMessage::where('created_at', '<', now()->subHours(24))->get();

        foreach ($expired as $sos) {
            $filePath = public_path('sos-audio/' . $sos->audio_path);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $sos->delete();
        }

        $this->info("Purged {$expired->count()} expired SOS message(s).");
    }
}
