<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedules = [
            [
                'schedule_start_date' => '2022-02-21',
                'schedule_start_time' => '02:40:00',
                'schedule_end_date' => '2022-02-21',
                'schedule_end_time' => '04:20:00',
                'show_id' => 1
            ],
            [
                'schedule_start_date' => '2022-03-29',
                'schedule_start_time' => '12:55:00',
                'schedule_end_date' => '2022-03-29',
                'schedule_end_time' => '14:15:00',
                'show_id' => 1
            ],
            [
                'schedule_start_date' => '2022-04-03',
                'schedule_start_time' => '19:30:00',
                'schedule_end_date' => '2022-04-03',
                'schedule_end_time' => '21:35:00',
                'show_id' => 1
            ],
            [
                'schedule_start_date' => '2022-04-08',
                'schedule_start_time' => '09:30:00',
                'schedule_end_date' => '2022-04-08',
                'schedule_end_time' => '11:05:00',
                'show_id' => 1
            ],
            [
                'schedule_start_date' => '2022-02-21',
                'schedule_start_time' => '00:40:00',
                'schedule_end_date' => '2022-02-21',
                'schedule_end_time' => '01:45:00',
                'show_id' => 4
            ],
            [
                'schedule_start_date' => '2022-02-21',
                'schedule_start_time' => '06:00:00',
                'schedule_end_date' => '2022-02-21',
                'schedule_end_time' => '07:30:00',
                'show_id' => 6
            ],
            [
                'schedule_start_date' => '2022-05-25',
                'schedule_start_time' => '10:00:00',
                'schedule_end_date' => '2022-05-25',
                'schedule_end_time' => '10:30:00',
                'show_id' => 5,
                'instalment_id' => 1,
            ],
        ];

        foreach($schedules as $schedule){
            Schedule::create($schedule);
        }
    }
}
