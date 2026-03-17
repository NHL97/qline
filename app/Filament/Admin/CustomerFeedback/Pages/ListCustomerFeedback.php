<?php

namespace App\Filament\Admin\CustomerFeedback\Pages;

use App\Filament\Admin\CustomerFeedback\CustomerFeedbackResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerFeedback extends ListRecords
{
    protected static string $resource = CustomerFeedbackResource::class;
}
