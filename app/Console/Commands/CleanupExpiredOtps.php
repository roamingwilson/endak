<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OtpCode;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'حذف رموز OTP المنتهية الصلاحية';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = OtpCode::cleanupExpired();

        $this->info("تم حذف {$deletedCount} رمز OTP منتهي الصلاحية.");

        return Command::SUCCESS;
    }
}
