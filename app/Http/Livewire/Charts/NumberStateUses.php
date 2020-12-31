<?php

namespace App\Http\Livewire\Charts;

use App\Models\CountDaily;
use Carbon\Carbon;
use Livewire\Component;

class NumberStateUses extends Component
{
    public  $laggedCount;
    public  $laggedDate;
    public  $stateLagDays;
    public  $secondaryLagDays;

    protected $listeners = [
        'updatedToDate',
    ];

    public function mount() {
        $date = Carbon::now();
        $this->findLag($date);
        $this->stateLagDays = 7;
        $this->secondaryLagDays = 2;
    }


    public function render()
    {
        $this->getAverageCountForLaggedDay();
        return view('livewire.charts.number-state-uses');
    }


    public function updatedToDate($toDate)
    {
        $this->findLag($toDate);
    }


    private function findLag($toDate) {
        $totalDaysBack = $this->stateLagDays+$this->secondaryLagDays;
        $rawToDate = (is_object($toDate)) ? $toDate :
            Carbon::createFromFormat('Y-m-d', $toDate)->setTimezone('America/Los_Angeles')->endOfDay();
        $laggedDate    = $rawToDate->copy()->subDays($totalDaysBack);
        $this->laggedDate = $laggedDate->toDateString();
    }


    private function getAverageCountForLaggedDay()
    {
        $perHunderedThousandModifier = 1.36;
        $currentTime = Carbon::now()->toTimeString();
        $rawToEnd    = Carbon::createFromFormat('Y-m-d H:i:s', $this->laggedDate.' '.$currentTime)
                             ->setTimezone('America/Los_Angeles');
        $newestDate  = $rawToEnd->copy()->endOfDay();
        $oldiestDate = $rawToEnd->copy()->subDays($this->stateLagDays)->startOfDay();
        $totalCounts = CountDaily::orderBy('created_at')
                                 ->where('created_at', '>=', $oldiestDate)
                                 ->where('created_at', '<=', $newestDate);
        $summedCount = $totalCounts->sum('count');
        $this->laggedCount = (empty($summedCount) || empty($this->stateLagDays) || empty($perHunderedThousandModifier)) ? 0
        : round(($summedCount / $this->stateLagDays)/$perHunderedThousandModifier, 2);
    }
}
