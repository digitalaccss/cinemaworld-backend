<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

use Laravel\Nova\Dashboards\Main; // 1st menu section / item in sidebar
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Menu\MenuItem;

use App\Nova\Home;
use App\Nova\Film;
use App\Nova\Docufilm;
use App\Nova\Short;
use App\Nova\Serie;
use App\Nova\Instalment;
use App\Nova\Cast;
use App\Nova\Director;
use App\Nova\ShowType;
use App\Nova\CarouselType;
use App\Nova\Tag;
use App\Nova\Carousel;
use App\Nova\Campaign;
use App\Nova\Schedule;
use App\Nova\Region;
use App\Nova\Country;
use App\Nova\Genre;
use App\Nova\Language;
use App\Nova\Accolade;
use App\Nova\Critic;
use App\Nova\Partnership;
use App\Nova\Event;
use App\Nova\PartnershipEventType;
use App\Nova\User;
use App\Nova\ScheduleFile;
use App\Nova\Blog;
use App\Nova\GeneralSetting;
use App\Nova\AboutUs;

use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

use SimonHamp\LaravelNovaCsvImport\LaravelNovaCsvImport;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // customize the main menu / sidebar
        Nova::mainMenu(function (Request $req){
            // 1 main menu can have many menu sections
            return [
                // 1 menu section can have many menu items
                MenuSection::dashboard(Main::class)->icon('home'),

                MenuSection::make('Pages', [
                    MenuItem::resource(Home::class),
                    MenuItem::resource(AboutUs::class)
                ])->icon('collection')->collapsable(),

                MenuSection::make('Shows', [
                    MenuItem::resource(Film::class),
                    MenuItem::resource(Docufilm::class),
                    MenuItem::resource(Short::class),
                    MenuItem::resource(Serie::class),
                    MenuItem::resource(Instalment::class),
                    MenuItem::resource(Cast::class),
                    MenuItem::resource(Director::class),
                    MenuItem::resource(Accolade::class),
                    // MenuItem::resource(Critic::class),
                    // MenuItem::resource(ShowType::class),
                    // MenuItem::resource(CarouselType::class),
                    MenuItem::resource(Schedule::class),
                    MenuItem::resource(Carousel::class),
                    MenuItem::resource(Campaign::class),
                    MenuItem::resource(Tag::class),
                    MenuItem::resource(ScheduleFile::class)
                ])->icon('document-text')->collapsable(),

                MenuSection::make('Categories', [
                    MenuItem::resource(Region::class),
                    MenuItem::resource(Country::class),
                    MenuItem::resource(Genre::class),
                    MenuItem::resource(Language::class)
                ])->icon('view-grid')->collapsable(),

                //MenuSection::make('Partnership & Events', [
                MenuSection::make('Blogs', [
                    // MenuItem::resource(Partnership::class),
                    // MenuItem::resource(Event::class),
                    MenuItem::resource(Blog::class),
                    // MenuItem::resource(PartnershipEventType::class)
                ])->icon('newspaper')->collapsable(),

                MenuSection::make('Subscribe Partner')->icon('newspaper')->path('/resources/subscribe-partners'),

                MenuSection::make('Users')->icon('users')->path('/resources/users'),

                MenuSection::make('Settings')->icon('cog')->path('/resources/settings')
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            // return in_array($user->email, [
                //
            // ]);
            // allow any registered user in the database to access nova
            return true;
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new LaravelNovaCsvImport,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
