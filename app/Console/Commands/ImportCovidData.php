<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\Covid;
use Maatwebsite\Excel\Excel;

class ImportCovidData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports an excel sheet of covid data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->title('Starting covid import');
        (new Covid\CovidImport())->withOutput($this->output)->import(storage_path('test.xlsx'));
        $this->output->success('Imported Covid data successful');
    }
}
