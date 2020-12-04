<div class="container mx-auto space-y-4 p-4 sm:p-0">
    <style>
        .card {
            padding-bottom: 4em;
            min-width: 80em !important;
        }
    </style>
    <ul class="flex flex-col sm:flex-row sm:space-x-8 sm:items-center">
    <li>
        <label for="from-date" class="block text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4" for="select-ship-size">From:</label>
        <input type="text" wire:model="fromDate" id="from-date" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
    </li>
    <li>
        <label for="to-date" class="block text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4" for="select-ship-size">To:</label>
        <input type="text" wire:model="toDate" id="to-date" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
    </li>
    </ul>
    @if(! empty($lineChartModelCountLimited))
        <div class="card">
            <div class="card-body">
                @if($lineChartModelCountLimited->data->isEmpty())
                    <h4>No Data</h4>
                @else
                    <livewire:livewire-line-chart
                        key="{{ $lineChartModelCountLimited->reactiveKey() }}"
                        :line-chart-model="$lineChartModelCountLimited"
                    />
                @endif
            </div>
        </div>
    @endif
    @if(! empty($lineChartModelCountAll))
        <div class="card">
            <div class="card-body">
                @if($lineChartModelCountAll->data->isEmpty())
                    <h4>No Data</h4>
                @else
                    <livewire:livewire-line-chart
                        key="{{ $lineChartModelCountAll->reactiveKey() }}"
                        :line-chart-model="$lineChartModelCountAll"
                    />
                @endif
            </div>
        </div>
    @endif
</div>
