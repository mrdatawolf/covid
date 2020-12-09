<?php

namespace App\Http\Livewire\Charts;

use App\Models\CountDaily;
use Carbon\Carbon;
use Livewire\Component;

class TuesdayAverage extends Component
{
    public  $tuesdayCount;
    public  $tuesdayDate;

    protected $listeners = [
        'updatedToDate',
    ];

    public function mount() {
        $date = Carbon::now();
        $this->findLastTuesday($date);
    }


    public function render()
    {
        $this->getAverageCountForTuesday();
        return view('livewire.charts.tuesday-average');
    }


    public function updatedToDate($toDate)
    {
        $this->findLastTuesday($toDate);
    }


    private function findLastTuesday($toDate) {
        $rawToDate = (is_object($toDate)) ? $toDate :
            Carbon::createFromFormat('Y-m-d', $toDate)->setTimezone('America/Los_Angeles')->endOfDay()->setTimezone('America/Los_Angeles');
        $tuesday    = $rawToDate->copy()->subDays(7)->startOfWeek(Carbon::TUESDAY);
        $this->tuesdayDate = $tuesday->toDateString();
    }


    private function getAverageCountForTuesday($daysBack = 7)
    {
        $currentTime = Carbon::now()->toTimeString();
        $rawToEnd    = Carbon::createFromFormat('Y-m-d H:i:s', $this->tuesdayDate.' '.$currentTime)
                             ->setTimezone('America/Los_Angeles');
        $newestDate  = $rawToEnd->copy()->endOfDay();
        $oldiestDate = $rawToEnd->copy()->subDays($daysBack)->startOfDay();
        $totalCounts = CountDaily::orderBy('created_at')
                                 ->where('created_at', '>=', $oldiestDate)
                                 ->where('created_at', '<=', $newestDate);
        $this->tuesdayCount = ceil($totalCounts->sum('count') / $daysBack);
    }
}
