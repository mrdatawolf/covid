<div class="container mx-auto space-y-4 p-4 sm:p-0">
    <style>
        .card {
            padding-bottom: 4em;
            min-width: 80em !important;
        }
    </style>
    <ul class="flex flex-col sm:flex-row sm:space-x-8 sm:items-center">
        <li>
            <label for="from-date" class="text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4">From:</label>
            <input type="text" onChange="changeFromDate(this.value)" wire:model="fromDate" id="from-date" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" readonly>
        </li>
        <li>
            <label for="to-date" class="text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4">To:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" onChange="changeToDate(this.value)" wire:model="toDate" id="to-date" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" readonly>
        </li>
    </ul>
    <hr>
    <ul class="flex flex-col sm:flex-row sm:space-x-8 sm:items-center">
        <li>
            @livewire('charts.seven-day-average')
        </li>
        <li>
            @livewire('charts.number-county-uses')
        </li>
        <li>
            @livewire('charts.number-state-uses')
        </li>
    </ul>
    <ul class="flex flex-col sm:flex-row sm:space-x-8 sm:items-center">
        <li>
        <!--@livewire('charts.extrapolate-out')-->
        </li>
    </ul>
    <hr>
    <div class="card">
        <div class="card-body">
            @if(is_null($lineChartModelCountLimited))
                <h4>No Data</h4>
            @else
                {!! $lineChartModelCountLimited->container() !!}
            @endif
        </div>
    </div>
    <hr>
    <div class="card">
        <div class="card-body">
            @if(is_null($lineChartModelCountMonthly))
                <h4>No Data</h4>
            @else
                {!! $lineChartModelCountMonthly->container() !!}
            @endif
        </div>
    </div>
    <hr>
    <div class="card">
        <div class="card-body">
            @if(is_null($lineChartModelCountAll))
                <h4>No Data</h4>
            @else
                {!! $lineChartModelCountAll->container() !!}
            @endif
        </div>
    </div>

    @section('scripts')
    <script>
        var pickerTo = new Pikaday({
            field: document.getElementById('to-date'),
            format: 'YYYY-MM-DD',
            toString(date, format) {
                // you should do formatting based on the passed format,
                // but we will just return 'D/M/YYYY' for simplicity
                const day = date.getDate();
                const month = date.getMonth() +1;
                const year = date.getFullYear();
                return `${year}-${month}-${day}`;
            },
            parse(dateString, format) {
                // dateString is the result of `toString` method
                const parts = dateString.split('/');
                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) -1;
                const year = parseInt(parts[2], 10);
                return new Date(year, month, day);
            }
        });
        var pickerFrom = new Pikaday({
            field: document.getElementById('from-date'),
            format: 'YYYY-MM-DD',
            toString(date, format) {
                // you should do formatting based on the passed format,
                // but we will just return 'D/M/YYYY' for simplicity
                const day = date.getDate();
                const month = date.getMonth() +1;
                const year = date.getFullYear();
                return `${year}-${month}-${day}`;
            },
            parse(dateString, format) {
                // dateString is the result of `toString` method
                const parts = dateString.split('/');
                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) -1;
                const year = parseInt(parts[2], 10);
                return new Date(year, month, day);
            },
        });
        function changeToDate(value) {
            Livewire.emit('toDateChanged', value);
        }
        function changeFromDate(value) {
            Livewire.emit('fromDateChanged', value);
        }
    </script>
    {{ $lineChartModelCountLimited->script() }}
    {{ $lineChartModelCountAll->script() }}
    {{ $lineChartModelCountMonthly->script() }}
    @endsection
</div>
