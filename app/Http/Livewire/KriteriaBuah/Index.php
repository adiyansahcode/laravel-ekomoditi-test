<?php

declare(strict_types=1);

namespace App\Http\Livewire\KriteriaBuah;

use App\Models\KriteriaBuah;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Index extends Component
{
    /**
     * title layout page.
     */
    public string $titleLayout = 'Kriteria Buah';

    /**
     * form variable.
     */
    public int $dataId = 0;

    public array $dataIds = [];

    public ?string $name = null;

    /**
     * modal status.
     */
    public bool $createModal = false;

    public bool $updateModal = false;

    public bool $detailModal = false;

    public bool $deleteModal = false;

    /**
     * listen event.
     *
     * @var array
     */
    protected $listeners = [
        'create' => 'create',
        'update' => 'edit',
        'detail' => 'show',
        'delete' => 'deleteConfirm',
        'multipleDelete' => 'multipleDeleteConfirm',
    ];

    /**
     * mount variable.
     *
     * @return void
     */
    public function mount(): void
    {
        //
    }

    /**
     * render view page.
     *
     * @return View
     */
    public function render(): View
    {
        abort_if(Gate::denies('kriteriaBuahAccess'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('livewire.kriteria-buah.index')
            ->layoutData(['title' =>  $this->titleLayout]);
    }

    /**
     * reset input fields.
     *
     * @return void
     */
    private function resetInputFields(): void
    {
        $this->reset([
            'dataId',
            'dataIds',
            'name',
        ]);

        // These two methods do the same thing, they clear the error bag.
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * set input fields.
     *
     * @param int $id
     * @return void
     */
    private function setInputFields(int $id): void
    {
        $data = KriteriaBuah::findOrFail($id);
        $this->dataId = $data->id;
        $this->name = $data->name;
    }

    /**
     * Open the modal when create.
     *
     * @var array
     */
    public function create()
    {
        abort_if(Gate::denies('kriteriaBuahCreate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->resetInputFields();

        $this->createModal = true;
    }

    /**
     * Validate then Insert data.
     *
     * @return void
     */
    public function store()
    {
        abort_if(Gate::denies('kriteriaBuahCreate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('App\Models\KriteriaBuah', 'name')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) {
                    // check unique case sensitive
                    $data = KriteriaBuah::whereRaw('LOWER(name) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                }
            ],
        ]);

        $data = new KriteriaBuah();
        $data->name = $this->name;
        $data->save();

        session()->flash('message', 'data saved successfully.');

        $this->createModal = false;
        $this->resetInputFields();

        $this->emit('showMessage');
        $this->emit('refreshDatatable');
    }

    /**
     * Open the modal when edit.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id): void
    {
        abort_if(Gate::denies('kriteriaBuahUpdate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->resetInputFields();
        $this->setInputFields($id);

        $this->updateModal = true;
    }

    /**
     * Validate then Update data.
     *
     * @return void
     */
    public function update(): void
    {
        abort_if(Gate::denies('kriteriaBuahUpdate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('App\Models\KriteriaBuah', 'name')->ignore($this->dataId)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) {
                    // check unique case sensitive
                    $data = KriteriaBuah::where('id', '<>', $this->dataId)->whereRaw('LOWER(name) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                }
            ],
        ]);

        $data = KriteriaBuah::find($this->dataId);
        $data->name = $this->name;
        $data->save();

        session()->flash('message', 'data updated successfully.');

        $this->updateModal = false;
        $this->resetInputFields();

        $this->emit('showMessage');
        $this->emit('refreshDatatable');
    }

    /**
     * open detail data.
     *
     * @param int $id
     * @return void
     */
    public function show(int $id): void
    {
        abort_if(Gate::denies('kriteriaBuahDetail'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->resetInputFields();
        $this->setInputFields($id);

        $this->detailModal = true;
    }

    /**
     * open confirm message when delete.
     *
     * @param int $id
     * @return void
     */
    public function deleteConfirm(int $id): void
    {
        abort_if(Gate::denies('kriteriaBuahDelete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->resetInputFields();
        $this->setInputFields($id);

        $this->deleteModal = true;
    }

    /**
     * open confirm message when delete.
     *
     * @param array $id
     * @return void
     */
    public function multipleDeleteConfirm(array $id): void
    {
        abort_if(Gate::denies('kriteriaBuahDelete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $this->dataIds = $id;
        $this->deleteModal = true;
    }

    /**
     * delete data.
     *
     * @return void
     */
    public function delete(): void
    {
        abort_if(Gate::denies('kriteriaBuahDelete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!empty($this->dataIds)) {
            KriteriaBuah::whereIn('id', $this->dataIds)->delete();
        }

        if (!empty($this->dataId)) {
            KriteriaBuah::find($this->dataId)->delete();
        }

        session()->flash('message', 'data deleted successfully.');

        $this->deleteModal = false;
        $this->resetInputFields();

        $this->emit('showMessage');
        $this->emit('refreshDatatable');
        $this->emit('resetSelected');
    }
}
