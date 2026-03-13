<?php

namespace App\Filament\Admin\Businesses\Pages;

use App\Filament\Admin\Businesses\BusinessResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBusiness extends CreateRecord
{
    protected static string $resource = BusinessResource::class;
}
