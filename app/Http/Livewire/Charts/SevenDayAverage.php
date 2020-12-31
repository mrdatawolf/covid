<?php

namespace App\Http\Livewire\Charts;

use App\Models\CountDaily;
use Carbon\Carbon;
use Livewire\Component;

class SevenDayAverage extends Component
{
    public $sevenDaysEndDate;
    public $sevenDaysAverage;

    protected $listeners = [
        'updatedToDate',
    ];


    public function mount()
    {
        $countAll               = CountDaily::orderBy('created_at')->get();
        $latestActiveDate       = $countAll->last()->created_at;
        $lastActive             = $latestActiveDate->setTimezone('America/Los_Angeles');
        $this->sevenDaysEndDate = $lastActive->toDateString();
    }


    public function render()
    {
        $this->sevenDaysAverage = $this->getAverageCountOverDays();

        return view('livewire.charts.seven-day-average');
    }


    public function updatedToDate($toDate)
    {
        $this->sevenDaysEndDate = $toDate;
    }


    /**
     * @param int $daysBack
     *
     * @return false|float
     */
    private function getAverageCountOverDays($daysBack = 7)
    {
        $perHunderedThousandModifier = 1.36;
        $rawToEnd    = Carbon::createFromFormat('Y-m-d H:i:s', $this->sevenDaysEndDate.' 23:59:59', 'America/Los_Angeles');
        $newestDate  = $rawToEnd->copy()->timezone('UTC');
        $oldiestDate = $rawToEnd->copy()->timezone('UTC')->subDays($daysBack)->startOfDay();
        $totalCounts = CountDaily::orderBy('created_at')
                                 ->where('created_at', '>=', $oldiestDate)
                                 ->where('created_at', '<=', $newestDate);
        return round(($totalCounts->sum('count') / $daysBack)/$perHunderedThousandModifier, 1);
    }
}
