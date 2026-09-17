<?php

namespace App\Providers;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\DetailPersyaratanRepositoryInterface;
use App\Repositories\Contracts\GalleryRepositoryInterface;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\PelayananRepositoryInterface;
use App\Repositories\Contracts\PersyaratanRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\PublikasiRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Contracts\StrukturOrganisasiRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\DetailPersyaratanRepository;
use App\Repositories\Eloquent\GalleryRepository;
use App\Repositories\Eloquent\MenuRepository;
use App\Repositories\Eloquent\PelayananRepository;
use App\Repositories\Eloquent\PersyaratanRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\PublikasiRepository;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Eloquent\StrukturOrganisasiRepository;
use App\Repositories\Eloquent\UserRepository;
use App\View\Composers\SidebarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(StrukturOrganisasiRepositoryInterface::class, StrukturOrganisasiRepository::class);
        $this->app->bind(PelayananRepositoryInterface::class, PelayananRepository::class);
        $this->app->bind(PersyaratanRepositoryInterface::class, PersyaratanRepository::class);
        $this->app->bind(DetailPersyaratanRepositoryInterface::class, DetailPersyaratanRepository::class);
        $this->app->bind(GalleryRepositoryInterface::class, GalleryRepository::class);
        $this->app->bind(PublikasiRepositoryInterface::class, PublikasiRepository::class);
    }

    public function boot()
    {
        View::composer('partials.sidebar', SidebarComposer::class);
    }
}
