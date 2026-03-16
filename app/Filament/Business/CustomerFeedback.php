<?php

namespace App\Filament\Business;

use App\Models\CustomerFeedback as FeedbackModel;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class CustomerFeedback extends Page
{
    protected string $view = 'filament.business.customer-feedback';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Feedback';

    protected static ?int $navigationSort = 7;

    public float $avgRating = 0;
    public int $totalFeedback = 0;
    public array $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    public $recentFeedback = [];

    public function mount(): void
    {
        $businessId = Auth::user()->business_id;

        $this->totalFeedback = FeedbackModel::where('business_id', $businessId)->count();

        $this->avgRating = round(
            FeedbackModel::where('business_id', $businessId)->avg('rating') ?? 0,
            1
        );

        foreach ([1, 2, 3, 4, 5] as $star) {
            $this->ratingCounts[$star] = FeedbackModel::where('business_id', $businessId)
                ->where('rating', $star)
                ->count();
        }

        $this->recentFeedback = FeedbackModel::where('business_id', $businessId)
            ->with('queueEntry')
            ->latest()
            ->take(20)
            ->get()
            ->toArray();
    }
}