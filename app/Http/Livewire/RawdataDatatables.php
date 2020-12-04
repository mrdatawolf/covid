<?php namespace App\Http\Livewire;

use App\Models\CountDaily;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class RawdataDatatables extends LivewireDatatable
{
    public $model = CountDaily::class;


    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            DateColumn::name('created_at')->format('m/d/Y')->label('Creation Date')->filterable(),
            NumberColumn::name('count')->filterable()->label('Count'),
        ];
    }
}
