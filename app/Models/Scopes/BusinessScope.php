<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BusinessScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Only apply in business panel context
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Only scope for business panel users
        if (!$user->isBusinessPanel()) {
            return;
        }

        if ($user->business_id) {
            $builder->where($model->getTable() . '.business_id', $user->business_id);
        }
    }
}