<?php

namespace App\Http\Livewire\Charts;

use App\Models\CountDaily;
use Carbon\Carbon;
use Livewire\Component;

class NumberStateUses extends Component
{
    public  $laggedCount;
    public  $laggedDate;
    public  $secondaryLagDays;

    protected $listeners = [
        'updatedToDate',
    ];

    public function mount() {
        $date = Carbon::now();
        $this->findAWeekAgo($date);
        $this->secondaryLagDays = 2;
    }


    public function render()
    {
        $this->getAverageCountForLaggedDay();
        return view('livewire.charts.number-state-uses');
    }


    public function updatedToDate($toDate)
    {
        $this->findAWeekAgo($toDate);
    }


    private function findAWeekAgo($toDate) {
        $rawToDate = (is_object($toDate)) ? $toDate :
            Carbon::createFromFormat('Y-m-d', $toDate)->setTimezone('America/Los_Angeles')->endOfDay()->setTimezone('America/Los_Angeles');
        $tuesday    = $rawToDate->copy()->subDays(7);
        $this->laggedDate = $tuesday->toDateString();
    }


    private function getAverageCountForLaggedDay($daysBack = 7)
    {
        $totalLagDays = $this->secondaryLagDays + $daysBack;
        $perHunderedThousandModifier = 1.36;
        $currentTime = Carbon::now()->toTimeString();
        $rawToEnd    = Carbon::createFromFormat('Y-m-d H:i:s', $this->laggedDate.' '.$currentTime)
                             ->setTimezone('America/Los_Angeles');
        $newestDate  = $rawToEnd->copy()->endOfDay();
        $oldiestDate = $rawToEnd->copy()->subDays($daysBack)->startOfDay();
        $totalCounts = CountDaily::orderBy('created_at')
                                 ->where('created_at', '>=', $oldiestDate)
                                 ->where('created_at', '<=', $newestDate);
        $summedCount = $totalCounts->sum('count');
        $this->laggedCount = (empty($summedCount) || empty($daysBack) || empty($perHunderedThousandModifier)) ? 0
        : round(($summedCount / $totalLagDays)/$perHunderedThousandModifier, 2);
    }
}
