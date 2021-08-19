<?php

declare(strict_types=1);

namespace App\Http\Livewire\Transaksi;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\KriteriaBuah;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Index extends Component
{
    /**
     * title layout page.
     */
    public string $titleLayout = 'Transaksi';

    /**
     * list kriteriaBuah.
     */
    public array $kriteriaBuah;

    public array $kriteriaBuahId = [];

    /**
     * form variable.
     */
    public int $dataId = 0;

    public array $dataIds = [];

    public ?string $noTrans = null;

    public ?string $tanggal = null;

    public $divisi = 0;

    public $totalBuah = 0;

    public array $jumlah = [];

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
        $this->kriteriaBuah = KriteriaBuah::orderBy('name')->pluck('id', 'name')->toArray();

        foreach ($this->kriteriaBuah as $kriteriaBuahName => $kriteriaBuahId) {
            $this->jumlah[$kriteriaBuahId] = 0;
        }
    }

    /**
     * render view page.
     *
     * @return View
     */
    public function render(): View
    {
        abort_if(Gate::denies('transaksiAccess'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('livewire.transaksi.index')
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
            'noTrans',
            'tanggal',
            'divisi',
            'totalBuah',
            'jumlah',
        ]);

        foreach ($this->kriteriaBuah as $kriteriaBuahName => $kriteriaBuahId) {
            $this->jumlah[$kriteriaBuahId] = 0;
        }

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
        $data = Transaksi::findOrFail($id);
        $this->dataId = $data->id;
        $this->noTrans = $data->no_trans;
        $this->tanggal = ($data->tanggal) ? (new Carbon($data->tanggal))->isoFormat('DD-MM-YYYY') : null;
        $this->divisi = $data->divisi;
        $this->totalBuah = $data->total_buah;

        $dataDetail = $data->transaksiDetail()->get();
        foreach ($dataDetail as $dataDetail) {
            $this->jumlah[$dataDetail->kriteria_buah_id] = $dataDetail->jumlah;
        }
    }

    /**
     * Open the modal when create.
     *
     * @var array
     */
    public function create()
    {
        abort_if(Gate::denies('transaksiCreate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('transaksiCreate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate([
            'noTrans' => [
                'required',
                'string',
                'max:100',
                Rule::unique('App\Models\Transaksi', 'no_trans')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) {
                    // check unique case sensitive
                    $data = Transaksi::whereRaw('LOWER(no_trans) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                }
            ],
            'divisi' => [
                'required',
                'numeric',
            ],
            'tanggal' => [
                'nullable',
                'date',
                'date_format:d-m-Y',
            ],
            'jumlah' => [
                'required',
                'array',
            ],
            'jumlah.*' => [
                'required',
                'numeric',
            ],
        ]);

        $data = new Transaksi();
        $data->no_trans = $this->noTrans;
        $data->divisi = $this->divisi;
        $data->tanggal = (new Carbon($this->tanggal))->isoFormat('YYYY-MM-DD');
        $data->total_buah = 0;
        $data->save();

        $kriteriaBuah = KriteriaBuah::orderBy('name')->get();
        foreach ($kriteriaBuah as $kriteriaData) {
            $dataDetail = new TransaksiDetail();
            $dataDetail->jumlah = $this->jumlah[$kriteriaData->id];
            $dataDetail->transaksi_id = $data->id;
            $dataDetail->kriteria_buah_id = $kriteriaData->id;
            $dataDetail->save();
        }

        $data->total_buah = $data->getTotalBuah();
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
        abort_if(Gate::denies('transaksiUpdate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('transaksiUpdate'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate([
            'noTrans' => [
                'required',
                'string',
                'max:100',
                Rule::unique('App\Models\Transaksi', 'no_trans')->ignore($this->dataId)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
                function ($attribute, $value, $fail) {
                    // check unique case sensitive
                    $data = Transaksi::where('id', '<>', $this->dataId)->whereRaw('LOWER(no_trans) = ?', [Str::lower($value)])->first();
                    if ($data) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                }
            ],
            'divisi' => [
                'required',
                'numeric',
            ],
            'tanggal' => [
                'nullable',
                'date',
                'date_format:d-m-Y',
            ],
        ]);

        $data = Transaksi::find($this->dataId);
        $data->no_trans = $this->noTrans;
        $data->divisi = $this->divisi;
        $data->tanggal = (new Carbon($this->tanggal))->isoFormat('YYYY-MM-DD');
        $data->total_buah = $this->totalBuah;
        $data->save();

        $data->transaksiDetail()->delete();

        $kriteriaBuah = KriteriaBuah::orderBy('name')->get();
        foreach ($kriteriaBuah as $kriteriaData) {
            $dataDetail = new TransaksiDetail();
            $dataDetail->jumlah = $this->jumlah[$kriteriaData->id];
            $dataDetail->transaksi_id = $data->id;
            $dataDetail->kriteria_buah_id = $kriteriaData->id;
            $dataDetail->save();
        }

        $data->total_buah = $data->getTotalBuah();
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
        abort_if(Gate::denies('transaksiDetail'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('transaksiDelete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        abort_if(Gate::denies('transaksiDelete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        abort_if(Gate::denies('transaksiDelete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!empty($this->dataIds)) {
            Transaksi::whereIn('id', $this->dataIds)->delete();
        }

        if (!empty($this->dataId)) {
            Transaksi::find($this->dataId)->delete();
        }

        session()->flash('message', 'data deleted successfully.');

        $this->deleteModal = false;
        $this->resetInputFields();

        $this->emit('showMessage');
        $this->emit('refreshDatatable');
        $this->emit('resetSelected');
    }
}
