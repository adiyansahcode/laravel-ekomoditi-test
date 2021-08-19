<?php

declare(strict_types=1);

namespace App\Http\Livewire\KriteriaBuah;

use App\Models\KriteriaBuah;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;

class Datatables extends DataTableComponent
{
    /**
     * listen event.
     *
     * @var array
     */
    protected $listeners = [
        'refreshDatatable' => '$refresh',
        'resetSelected' => 'resetSelected',
    ];

    /**
     * The Table Name
     */
    protected string $tableName = 'kriteria_buah';

    /**
     * Table primary key
     */
    public string $primaryKey = 'id';

    /**
     * Setting show options to show column name
     */
    public bool $columnSelect = true;

    /**
     * setting defaut sorting column name
     */
    public string $defaultSortColumn = 'name';

    /**
     * setting defaut sorting direction
     */
    public string $defaultSortDirection  = 'asc';

    /**
     * setting defaut for sorting only one column
     */
    public bool $singleColumnSorting  = true;

    /**
     * setting for hide action when not selected row
     */
    public bool $hideBulkActionsOnEmpty = true;

    /**
     * Setting for pagination name
     */
    protected string $pageName = 'page';

    /**
     * Setting show all in pagination options
     */
    public bool $perPageAll = true;

    /**
     * Setting show per pagination options
     */
    public array $perPageAccepted = [
        5,
        10,
        25,
        50,
        100
    ];

    /**
     * Setting defaut data pagination page
     */
    public int $perPage  = 5;

    /**
     * setting for number column
     *
     * @var int
     */
    public int $number = 1;

    /**
     * setting show action if row selected
     */
    public array $bulkActions = [];

    /**
     * filters default values
     */
    public array $filters = [];

    /**
     * filters Setting
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Query for table data
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return KriteriaBuah::query();
    }

    /**
     * mount variable
     *
     * @return void
     */
    public function mount(): void
    {
        $action = [];

        if (Gate::allows('kriteriaBuahDelete')) {
            $action = Arr::add($action, 'deleteSelected', 'Delete');
        }

        $this->bulkActions = $action;
    }

    /**
     * List Of Table
     *
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Last Updated', 'updated_at')
                ->sortable()
                ->searchable(),
            Column::make('Actions'),
        ];
    }

    /**
     * view for datatables
     *
     * @return string
     */
    public function rowView(): string
    {
        return 'livewire.kriteria-buah.datatables';
    }

    /**
     * reset for bulk action
     *
     * @return void
     */
    public function resetSelected(): void
    {
        $this->selected = [];
        $this->resetBulk(); // Clear the selected rows
        $this->resetPage(); // Go back to page 1
    }

    /**
     * delete selected id from table
     *
     * @return void
     */
    public function deleteSelected(): void
    {
        $this->emit('multipleDelete', $this->selectedKeys());
    }
}
