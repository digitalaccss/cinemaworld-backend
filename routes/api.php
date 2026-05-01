<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\ShortController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\CastController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PartnershipEventController;
use App\Http\Controllers\ScheduleFileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SubscribePartnerController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DocufilmController;
use App\Http\Controllers\AboutUsController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy buildg your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function(Request $request){
    return $request->user();
});


// homepage
Route::controller(HomeController::class)->group(function(){
    // get featured show, banner and active carousels
    Route::get('/home', 'getHomepage');
    // get shows for each carousel
    Route::get('/carousels/{carouselID}', 'getCarouselShows');
    // get 3 latest partnership and events
    Route::get('/latest-partnership-events', 'getLatestPartnershipEvents');

    Route::get('/latest-blogs', 'getLatestBlogs');
});

// about us
Route::controller(AboutUsController::class)->group(function(){
    // get featured show, banner and active carousels
    Route::get('/about', 'getAboutUs');
});

// campaigns
Route::controller(CampaignController::class)->group(function(){
    // get all campaigns
    Route::get('/campaigns', 'getAllCampaigns');
    // get a campaign
    Route::get('/campaigns/{slug}', 'getCampaign');
});

// search
Route::controller(SearchController::class)->group(function(){
    // predictive and regular search for films, series, cast, directors
    Route::get('/search', 'search'); // use laravel scout and meili search engine?
});


// filter all films, series, shorts according to its category e.g. region, countries, genres, languages
Route::controller(FilterController::class)->group(function(){
    Route::get('/filters', 'getAllFilters');
});

// films
Route::controller(FilmController::class)->group(function(){
    // get all films
    Route::get('/films', 'getAllFilms');
    // get a film
    Route::get('/films/{slug}', 'getFilm');
});

// docufilms
Route::controller(DocufilmController::class)->group(function(){
    // get all docu films
    Route::get('/docufilms', 'getAllDocufilms');
    // get a docu film
    Route::get('/docufilms/{slug}', 'getDocufilm');
});

// shorts
Route::controller(ShortController::class)->group(function(){
    // get all shorts
    Route::get('/shorts', 'getAllShorts');
    // get a short
    Route::get('/shorts/{slug}', 'getShort');
});

// series
Route::controller(SerieController::class)->group(function(){
    // get all films
    Route::get('/series', 'getAllSeries');
    // get a series
    Route::get('/series/{slug}', 'getSeries');
    // get a series instalment e.g. /series/the-blind-detective/the-dead-girls-from-vienna
    Route::get('/series/{seriesSlug}/{slug}', 'getSeriesInstalment');

    // get all instalments associated with a series??? e.g. /series/the-blind-detective/instalments
    // Route::get('/series/{slug}/instalments', 'getSeriesInstalments');
});

// directors
Route::controller(DirectorController::class)->group(function(){
    // get a director and the films, shorts and series that it directed
    Route::get('/directors/{slug}', 'getDirector');
});

// cast
Route::controller(CastController::class)->group(function(){
    // get a cast and the films, shorts and series that it acted in
    Route::get('/casts/{slug}', 'getCast');
});

// schedules
Route::controller(ScheduleController::class)->group(function(){
    // get a schedule and its corresponding shows e.g. ?date=2022-02-21
    Route::get('/schedules', 'getScheduleShows');
    // get now playing show and the datetimes that it plays next
    Route::get('/schedules/now-playing',  'getNowPlayingShow');
});

// partnership and events
Route::controller(PartnershipEventController::class)->group(function(){
    // get all partnership and events according to its type e.g. all, partnership, events, archive
    Route::get('/partnership-events', 'getAllPartnershipEvents');
    // get a partnership or event
    Route::get('/partnership-events/{slug}', 'getPartnershipEvent');
});

// blog
Route::controller(BlogController::class)->group(function(){
    // get all partnership and events according to its type e.g. all, partnership, events, archive
    Route::get('/blogs', 'getAllBlogs');
    // get a partnership or event
    Route::get('/blogs/{slug}', 'getBlog');
});

// schedule file
Route::controller(ScheduleFileController::class)->group(function(){
    // get all schedule files
    Route::get('/schedule-download', 'getScheduleFile');
});

// subscribe partner
Route::controller(SubscribePartnerController::class)->group(function(){
    // get all subscribe partner
    Route::get('/subscribe', 'getSubscribePartners');
});

// meta title and description
Route::controller(SiteController::class)->group(function(){
    // get site meta
    Route::get('/setting/{settingID}', 'getSetting');
});