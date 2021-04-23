<?php namespace App\Http\Livewire\Charts;

use App\Models\Location;
use Livewire\Component;
use App\Models\CountDaily;
use Carbon\Carbon;
use ArielMejiaDev\LarapexCharts\LarapexChart;

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
    }


    private function makeLineChartCountAll()
    {
        $chart = $this->makeLineChartModel('All data for '.$this->location.' '.ucfirst($this->locationType), true);

        $this->lineChartModelCountAll = empty(json_decode($chart->dataset())[0]->data) ? null : $chart;
    }


    private function makeLineChartCountLimited()
    {
        $chart = $this->makeLineChartModel('Limited data (From '.$this->fromDate.' To '.$this->toDate.') for '.$this->location.' '.ucfirst($this->locationType));

        $this->lineChartModelCountLimited = empty(json_decode($chart->dataset())[0]->data) ? null : $chart;
    }


    private function makeLineChartModel($title = '', $returnAll = false): ?\ArielMejiaDev\LarapexCharts\LineChart
    {
        $daysFrom = $this->rawFrom->copy()->startOfDay()->timezone('UTC');
        $daysTo = $this->rawTo->copy()->endOfDay()->timezone('UTC');
        $query = ($returnAll) ? \App\Models\CountDaily::query() :  \App\Models\CountDaily::query()->whereBetween('created_at', [$daysFrom, $daysTo]);
        $data = $query->pluck('count');
        $dates = $query->pluck('created_at');
        $labels = [];
        foreach ($dates as $date) {
            $labels[] = $date->format('M d');
        }

        $chart = (new LarapexChart)->lineChart()
                                   ->setTitle($title)
                                   ->setHeight('300')
                                   ->addLine('New cases', $data->toArray())
                                   ->setLabels($labels);

       return ($data->isEmpty()) ? null : $chart;
    }
}
