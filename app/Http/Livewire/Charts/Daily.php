<?php namespace App\Http\Livewire\Charts;

use App\Models\Location;
use Livewire\Component;
use App\Models\CountDaily;
use Carbon\Carbon;
use Asantibanez\LivewireCharts\Models\LineChartModel;

class Daily extends Component
{
    public  $count;
    public  $fromDate;
    public  $toDate;
    public  $rawTo;
    public  $rawFrom;
    public  $location;
    public  $locationType;
    public  $currentTime;
    public  $latestActiveDate;
    public  $earliestActiveDate;
    public  $countAll;
    public  $types    = ['count'];
    public  $colors   = ['average' => '#66DA26', 'sum' => '#fc8181', 'count' => '#f6ad55', 'amount' => '#cbd5e0'];
    public  $firstRun = true;
    private $lineChartModelCountAll;
    private $lineChartModelCountLimited;
    private $lineChartModelCount7;
    private $lineChartModelCount3;


    protected $listeners = [
        'toDateChanged',
        'fromDateChanged'
        /*'onPointClick'  => 'handleOnPointClick',
        'onSliceClick'  => 'handleOnSliceClick',
        'onColumnClick' => 'handleOnColumnClick'*/
    ];

    public function mount()
    {
        $location                 = (\Auth::check()) ? \Auth::user()->location()->first() : Location::find(1);
        $this->location           = $location->title;
        $this->locationType       = $location->state;
        $this->countAll           = CountDaily::orderBy('created_at')->get();
        $this->count              = CountDaily::orderBy('id')->get();
        $this->latestActiveDate   = $this->countAll->last()->created_at;
        $this->earliestActiveDate   = $this->countAll->first()->created_at;
        $this->updateRawTo();
        $this->updateRawFrom();
    }


    public function render()
    {
        $this->updateLineCharts();
        $this->firstRun = false;

        return view('livewire.charts.daily')->with([
            'lineChartModelCountLimited' => $this->lineChartModelCountLimited,
            'lineChartModelCount3'       => $this->lineChartModelCount3,
            'lineChartModelCount7'       => $this->lineChartModelCount7,
            'lineChartModelCountAll'     => $this->lineChartModelCountAll,
        ]);
    }


    /**
     * note: this is here so the javascipt date pickers change can fire.
     * @param $value
     */
    public function toDateChanged($value) {
        $this->toDate = $value;
        $this->updatedToDate();
    }


    /**
     * note: this is here so the javascipt date pickers change can fire.
     * @param $value
     */
    public function fromDateChanged($value) {
        $this->fromDate = $value;
        $this->updatedFromDate();
    }


    public function updatedToDate()
    {
        $this->updateRawTo();
        $this->emit('updatedToDate', $this->toDate);
    }


    public function updatedfromDate()
    {
        $this->updateRawFrom();
        $this->emit('updatedFromDate', $this->fromDate);
    }


    private function updateRawTo()
    {
        $rawTo              = (empty( $this->toDate)) ? $this->latestActiveDate->setTimezone('America/Los_Angeles') :
            Carbon::createFromFormat('Y-m-d H:i:s', $this->toDate.' '.$this->currentTime)
                  ->setTimezone('America/Los_Angeles')->endOfDay();

        $this->rawTo = $rawTo->copy()->timezone('UTC');
        $this->currentTime        = $rawTo->toTimeString();
        $this->toDate = $rawTo->toDateString();
    }


    private function updateRawFrom()
    {
        $this->rawFrom            = (empty( $this->fromDate)) ? $this->rawTo->copy()->subMonths(3)->startOfMonth() :
            Carbon::createFromFormat('Y-m-d H:i:s', $this->fromDate.' '.$this->currentTime)
                  ->setTimezone('America/Los_Angeles')->startOfDay();
        $this->fromDate           = $this->rawFrom->copy()->toDateString();
    }


    private function updateLineCharts()
    {
        $this->makeLineChartCountAll();
        $this->makeLineChartCountLimited();
        //$this->makeLineChartCount7();
        //$this->makeLineChartCount3();
    }


    private function makeLineChartCountAll()
    {
        $this->lineChartModelCountAll = $this->makeLineChartModel('count', $this->countAll,
            'All data for '.$this->location.' '.ucfirst($this->locationType));
    }


    private function makeLineChartCountLimited()
    {
        $countDateLimited = CountDaily::orderBy('created_at')
                                      ->whereBetween('created_at', [$this->rawFrom, $this->rawTo])
                                      ->get();

        $this->lineChartModelCountLimited = $this->makeLineChartModel('count', $countDateLimited,
            'Limited data (From '.$this->fromDate.' To '.$this->toDate.') for '.$this->location.' '.ucfirst($this->locationType));
    }


    private function makeLineChartCount3()
    {
        $countAllAveraged3 = $this->getAverageOverDays(3);

        $this->lineChartModelCount3 = $this->makeLineChartModel('count', $countAllAveraged3,
            'Averaged over 3 days data for '.$this->location.' '.ucfirst($this->locationType));
    }


    private function makeLineChartCount7()
    {
        $countAllAveraged7 = $this->getAverageOverDays(7);

        $this->lineChartModelCount7 = $this->makeLineChartModel('count', $countAllAveraged7,
            'Averaged over 7 days data for '.$this->location.' '.ucfirst($this->locationType));
    }


    private function makeLineChartModel($type, $count, $title = '')
    {
        return (in_array($type, $this->types)) ? $count->reduce(function (LineChartModel $lineChartModel, $data) use (
            $count,
            $type
        ) {
            $index = $data->created_at->timezone('America/Los_Angeles')->toDateString();

            $dailyCount = (int)$data->count;

            return $lineChartModel->addPoint($index, $dailyCount, ['id' => $index]);
        }, (new LineChartModel())->setTitle($title)
                                 ->setAnimated($this->firstRun)
                                 ->withOnPointClickEvent('onPointClick')) : null;
    }


    /**
     * @param $dayRange
     *
     * @return array
     */
    private function getAverageOverDays($dayRange): array
    {
        $averageCount = [];
        foreach ($this->countAll as $key => $count) {
            if ( ! empty($count->created_at)) {
                $averageCount[$count->created_at->toDateString()] = $this->countAll->where('created_at', '>=',
                    Carbon::createFromFormat('Y-m-d H:i:s', $count->created_at.''.$this->currentTime)
                          ->subDays($dayRange)
                          ->startOfDay())
                                                                                   ->where('created_at', '<=',
                                                                                       Carbon::createFromFormat('Y-m-d H:i:s',
                                                                                           $count->created_at)
                                                                                             ->endOfDay())
                                                                                   ->average('count');
            }
        }

        return $averageCount;
    }
}
