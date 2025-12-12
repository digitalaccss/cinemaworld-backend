<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
use App\Models\Show;
use App\Models\Instalment;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            // $shows = Show::where('expiry_date', '<', Carbon::today('Asia/Singapore'))->where('show_type_id', '!=', 2)->where('is_publish', true)->get();
            // $instalments = Instalment::where('expiry_date', '<', Carbon::today('Asia/Singapore'))->where('is_publish', true)->get();

            $shows = Show::where('expiry_date', '<', Carbon::today('Asia/Singapore'))->where('show_type_id', '!=', 2)->get();
            $instalments = Instalment::where('expiry_date', '<', Carbon::today('Asia/Singapore'))->get();
            $series = Show::where('show_type_id', 2)->get();

            if(count($shows) >= 1){
                foreach($shows as $show){
                  if(!$show->sub_title){
                    $show->sub_title = "This show is currently unavailable";
                    $show->save();
                  }
                    
                }
            }

            if(count($instalments) >= 1){
                foreach($instalments as $instalment){
                  if(!$instalment->sub_title){
                    $instalment->sub_title = "This show is currently unavailable";
                    $instalment->save();
                  } 
                }
            }

            if(count($series) >= 1){
                foreach($series as $serie){
                    $serieInstalments = Instalment::where('series_id', $serie->id)->where('expiry_date', '>=', Carbon::today('Asia/Singapore'))->get();
                    if(count($serieInstalments) < 1){
                      if(!$serie->sub_title){
                        $serie->sub_title = "This show is currently unavailable";
                        $serie->save();
                      }
                    }
                }
            }

        })->everyMinute();
        // })->everyFiveMinutes();

        //$schedule->command('daily:update')->everyMinute();
        //echo 'today date = '.Carbon::now();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
