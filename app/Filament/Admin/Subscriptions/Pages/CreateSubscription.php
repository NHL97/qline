<?php

namespace App\Filament\Admin\Subscriptions\Pages;

use App\Filament\Admin\Subscriptions\SubscriptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;
}
