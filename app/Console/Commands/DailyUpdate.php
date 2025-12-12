<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Show;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update shows and instalments status daily';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Show::where('expiry_date', '<', Carbon::today())->update(['is_publish' => false]);
        DB::table('shows')->where('expiry_date', '<', Carbon::today('Asia/Singapore'))->update(['is_publish' => false]);
        DB::table('instalments')->where('expiry_date', '<', Carbon::today('Asia/Singapore'))->update(['is_publish' => false]);

        //return 0;
    }
}
