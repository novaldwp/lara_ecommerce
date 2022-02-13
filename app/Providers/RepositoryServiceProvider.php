<?php

namespace App\Providers;

use App\Interfaces\DataTrainingRepositoryInterface;
use App\Interfaces\NegativeWordRepositoryInterface;
use App\Interfaces\PositiveWordRepositoryInterface;
use App\Interfaces\SentimentAnalysisRepositoryInterface;
use App\Interfaces\SliderRepositoryInterface;
use App\Repositories\DataTrainingRepository;
use App\Repositories\NegativeWordRepository;
use App\Repositories\PositiveWordRepository;
use App\Repositories\SentimentAnalysisRepository;
use App\Repositories\SliderRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);
        $this->app->bind(PositiveWordRepositoryInterface::class, PositiveWordRepository::class);
        $this->app->bind(NegativeWordRepositoryInterface::class, NegativeWordRepository::class);
        $this->app->bind(DataTrainingRepositoryInterface::class, DataTrainingRepository::class);
        $this->app->bind(SentimentAnalysisRepositoryInterface::class, SentimentAnalysisRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
