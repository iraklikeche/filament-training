<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', Post::count()),

            Stat::make('Posts This Month', Post::whereMonth('created_at', now()->month)->count()),

            Stat::make('Latest Post', Post::latest('created_at')->first()?->title ?? 'No Posts Yet'),
        ];
    }
}
