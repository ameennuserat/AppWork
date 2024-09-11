<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\ClientServiceController;
use App\repository\ClientOrderRepo;
use App\interfaces\CurdRepoInterface;
class curdRepoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->when(ClientServiceController::class)
          ->needs(CurdRepoInterface::class)
          ->give(function () {
              return new ClientOrderRepo();
          });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
