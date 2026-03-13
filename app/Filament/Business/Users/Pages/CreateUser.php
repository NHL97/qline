<?php

namespace App\Filament\Business\Users\Pages;

use App\Filament\Business\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['business_id']       = Auth::user()->business_id;
        $data['role']              = 'business_staff';
        $data['email_verified_at'] = now();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}