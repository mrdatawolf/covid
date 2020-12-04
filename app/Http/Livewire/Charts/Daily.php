<?php namespace App\Http\Livewire\Charts;

use Livewire\Component;
use App\Models\CountDaily;
use Carbon\Carbon;
use Asantibanez\LivewireCharts\Models\LineChartModel;

class Daily extends Component
{
    public $count;
    public $fromDate;
    public $toDate;
    public $types    = ['count'];
    public $colors   = ['average' => '#66DA26', 'sum' => '#fc8181', 'count' => '#f6ad55', 'amount' => '#cbd5e0'];
    public $firstRun = true;

    protected $listeners = [
        'onPointClick'  => 'handleOnPointClick',
        'onSliceClick'  => 'handleOnSliceClick',
        'onColumnClick' => 'handleOnColumnClick',
    ];

    public function mount()
    {
        $this->count  = CountDaily::orderBy('id')->get();
        $this->fromDate          = Carbon::now()->subMonths(3)->startOfMonth()->toDateString();
        $this->toDate            = Carbon::now()->toDateString();
    }
    public function render()
    {
        $fromDate = Carbon::createFromDate($this->fromDate)->startOfDay();
        $toDate = Carbon::createFromDate($this->toDate)->endOfDay();
        $countAll  = CountDaily::orderBy('created_at')->get();
        $countDateLimited  = CountDaily::orderBy('created_at')->whereBetween('created_at', [$fromDate, $toDate])->get();
        $lineChartModelCountAll   = $this->makeLineChartModel('count', $countAll, 'All data');
        $lineChartModelCountLimited   = $this->makeLineChartModel('count', $countDateLimited, 'Limited data (From ' . $this->fromDate . ' To ' . $this->toDate . ')');
        $this->firstRun = false;

        return view('livewire.charts.daily')->with(['lineChartModelCountLimited' => $lineChartModelCountLimited, 'lineChartModelCountAll' => $lineChartModelCountAll]);
    }

    private function makeLineChartModel($type, $count, $title = '')
    {
        return (in_array($type, $this->types)) ? $count->reduce(function (LineChartModel $lineChartModel, $data) use (
            $count,
            $type
        ) {
            $index   = $data->created_at->toDateString();

            $dailyCount = (int)$data->count;

            return $lineChartModel->addPoint($index, $dailyCount, ['id' => $index]);
        }, (new LineChartModel())->setTitle($title)->setAnimated($this->firstRun)->withOnPointClickEvent('onPointClick')) : null;
    }
}
