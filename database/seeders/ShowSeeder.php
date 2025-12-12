<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Show;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $films = Film::factory()
        // ->count(3)
        // ->create();

        $shows = [
            [
                'title' => 'The Startup',
                'foreign_title' => 'The Startup',
                'slug' => 'the-startup',
                'show_type_id' => 1,
                // 'genres' => ["6"],
                'region_id' => 6,
                // 'countries' => ["5"],
                // 'languages' => ["5"],
                'release_year' => 2017,
                'runtime' => 93,
                'short_desc' => 'A true story of Matteo Achilli, an 18 year old boy, who designs an innovative job-searching app. Soon enough, his app brings him immense popularity...',
                'full_desc' => 'A true story of Matteo Achilli, an 18 year-old boy, who designs an innovative job-searching app. Though response is initially lacklustre, he perseveres. Soon enough, his app brings him immense popularity and wealth. But success comes with a hefty price.',
                'trivia_desc' => "Matteo Achilli is often dubbed as the 'Italian Mark Zuckerberg'. Stars rising young Italian actor, Andrea Arcangeli, from Danny Boyle's 'Trust'. The film is based on the true story of the young Italian self-made entrepreneur, Matteo Achilli and his Egomnia, a startup to offer and find work.",
                'trailer_link_url' => 'https://www.youtube.com/embed/bkEZkMu4aIE'
            ],
            [
                'title' => '4 Kings',
                'foreign_title' => '4 Könige',
                'slug' => '4-kings',
                'show_type_id' => 1,
                // 'genres' => ["4", "6"],
                'region_id' => 2,
                // 'countries' => ["3", "4", "5"],
                // 'languages' => ["3"],
                'release_year' => 2015,
                'runtime' => 98,
                'short_desc' => 'In this tragicomedy about the teething pains of adolescence, four youths find themselves locked up in a psychiatric clinic over Christmas...',
                'full_desc' => 'In this tragicomedy about the teething pains of adolescence, four youths find themselves locked up in a psychiatric clinic over Christmas. Each are fighting their own demons but with the help of Dr. Wolff, they find ways to heal and love once again.',
                'trivia_desc' => "Boasts a powerful cast of some of Germany's most interesting up-and-coming acting talents.",
                'trailer_link_url' => 'https://www.youtube.com/embed/L9-gIMwQXF0'
            ],
            [
                'title' => 'King Of The Belgians',
                'foreign_title' => 'King Of The Belgians',
                'slug' => 'king-of-the-belgians',
                'show_type_id' => 1,
                // 'genres' => ["4", "6"],
                'region_id' => 5,
                // 'countries' => ["2"],
                // 'languages' => ["4"],
                'release_year' => 2016,
                'runtime' => 94,
                'short_desc' => 'King Nicolas II of the Belgians is on a state visit to Istanbul when his country falls apart. With a solar storm shutting down air travel...',
                'full_desc' => 'King Nicolas II of the Belgians is on a state visit to Istanbul when his country falls apart. With a solar storm shutting down air travel and communications, he must travel by road across the Balkans to save his country.',
                // 'trivia_desc' => '',
                'trailer_link_url' => 'https://www.youtube.com/embed/VcWdnt5k2GQ'
            ],
            [
                'title' => 'The Carer',
                'foreign_title' => 'The Carer',
                'slug' => 'the-carer',
                'show_type_id' => 1,
                // 'genres' => ["4", "6"],
                'region_id' => 7,
                // 'countries' => ["6"],
                // 'languages' => ["4"],
                'release_year' => 2012,
                'runtime' => 85,
                'short_desc' => "A young theatre actress is offered the job as caretaker for a legendary stage actor. That is, until she finds out he's a grumpy old man.",
                'full_desc' => "Dorottya is a young theatre actress who's elated when offered the job as caretaker for the legendary stage actor Sir Michael Gifford. That is, until she finds out he's a grumpy and irascible old man. Yet, an unexpected friendship soon blossoms.",
                // 'trivia_desc' => '',
                'trailer_link_url' => 'https://www.youtube.com/embed/VPhqCK4lSaU'
            ],
            [
                'title' => 'The Blind Detective',
                'foreign_title' => 'Blind Ermittelt',
                'slug' => 'the-blind-detective',
                'show_type_id' => 2,
                // 'genres' => ["5"],
                'region_id' => 6,
                // 'countries' => ["2", "3"],
                // 'languages' => ["4"],
                // 'release_year' => 2012,
                // 'runtime' => 85,
                'short_desc' => 'The blind ex-commissioner Alexander Haller will never forget May 12, 2017. It is the day he lost his fiancée and his eyesight in a bomb attack...',
                'full_desc' => "The blind ex-commissioner Alexander Haller will never forget May 12, 2017. It is the day he lost his fiancée Kara and his eyesight in a bomb attack. When a mysterious note appears on a murder victim with exactly this date, his past catches up with him. When Haller's sister, Sophie, is abducted by a ruthless gang, he decides to pursue them without involving the police. Together with his assistant, Niko, they expertly use their wits and intelligence to outsmart the criminals and bring Sophie to safety.",
                // 'trivia_desc' => '',
                // 'trailer_link_url' => ''
            ],
            [
                'title' => 'The Postman',
                'foreign_title' => 'The Postman',
                'slug' => 'the-postman',
                'show_type_id' => 4,
                // 'genres' => ["5", "6"],
                'region_id' => 3,
                // 'countries' => ["6"],
                // 'languages' => ["2", "4],
                'release_year' => 2018,
                'runtime' => 15,
                'short_desc' => 'A rogue cop must disengage himself from drug trafficking or lose his marriage forever. When he chooses to act, he finds out life sometimes does not...',
                'full_desc' => 'A rogue cop must disengage himself from drug trafficking or lose his marriage forever. When he chooses to act, he finds out life sometimes does not offer a second chance.',
                'trivia_desc' => "'The Postman' is the only Singapore film thus far to have competed at the Bucharest International Film Festival, as of 2018.",
                'trailer_link_url' => 'https://www.youtube.com/embed/hBuJx3pnCcc'
            ],
        ];

        foreach ($shows as $show) {
            Show::create($show);
        }
    }
}
