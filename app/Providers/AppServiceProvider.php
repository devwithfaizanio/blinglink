<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $apiVersions = [
            'v1' => 'api/v1.php',
            'v2' => 'api/v2.php',
        ];
        // Loop through each API version and register it
        foreach ($apiVersions as $version => $fileName) {
            Scramble::registerApi($version, [
                'api_path' => 'api/' . $version,
                'file_name' => $fileName, // Assign the file name here
            ]);
        }

        Scramble::ignoreDefaultRoutes();


        //paginator
//        Paginator::useBootstrapFive();
//
//        /**
//         * Paginate collection
//         *
//         * @param int $perPage
//         * @param int $total
//         * @param int $page
//         * @param string $pageName
//         * @return array
//         */
//        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
//            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
//
//            return new LengthAwarePaginator(
//                $this->forPage($page, $perPage),
//                $total ?: $this->count(),
//                $perPage,
//                $page,
//                [
//                    'path' => LengthAwarePaginator::resolveCurrentPath(),
//                    'pageName' => $pageName,
//                ]
//            );
//        });

    }
}
