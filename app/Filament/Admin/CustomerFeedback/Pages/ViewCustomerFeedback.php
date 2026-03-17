<?php

namespace App\Filament\Admin\CustomerFeedback\Pages;

use App\Filament\Admin\CustomerFeedback\CustomerFeedbackResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomerFeedback extends ViewRecord
{
    protected static string $resource = CustomerFeedbackResource::class;
}
