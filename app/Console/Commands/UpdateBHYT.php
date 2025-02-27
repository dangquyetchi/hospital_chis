<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateBHYT extends Command
{
    protected $signature = 'insurance:update-expired';
    protected $description = 'Cập nhật trạng thái hết hạn cho thẻ bảo hiểm y tế';

    public function handle()
    {
        $today = Carbon::now()->toDateString();

        DB::table('health_insurances')
            ->where('expiry_date', '<', $today)
            ->update(['status' => 0]);

        $this->info('Cập nhật trạng thái hết hạn thành công.');
    }
}