<?php

declare(strict_types=1);

namespace App\Http\Livewire\LaporanTransaksi;

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
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    /**
     * title layout page.
     */
    public string $titleLayout = 'Laporan Transaksi';

    public array $transaksiTanggal = [];

    public array $kriteriaBuahName = [];

    public array $laporan = [];

    public array $laporanTotal = [];

    /**
     * form variable.
     */
    public int $dataId = 0;

    public array $dataIds = [];

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
        $this->transaksiTanggal = Transaksi::select('tanggal')->distinct()->orderBy('tanggal')->pluck('tanggal')->toArray();

        $this->kriteriaBuahName = KriteriaBuah::orderBy('name')->pluck('id', 'name')->toArray();

        $laporanTotal = [];
        $no = 0;
        foreach ($this->transaksiTanggal as $transaksiTanggalIndex => $transaksiTanggalData) {
            $laporan[$no]['tanggal'] = ($transaksiTanggalData) ? (new Carbon($transaksiTanggalData))->isoFormat('D MMMM YYYY') : null;

            $no2 = 0;
            foreach ($this->kriteriaBuahName as $kriteriaBuahNameData => $kriteriaBuahIdData) {
                $totalData = DB::table('transaksi')
                    ->select(DB::raw('SUM(transaksi_detail.jumlah) as total'))
                    ->join('transaksi_detail', 'transaksi_detail.transaksi_id', '=', 'transaksi.id')
                    ->whereNull('transaksi.deleted_at')
                    ->whereNull('transaksi_detail.deleted_at')
                    ->where('transaksi.tanggal', $transaksiTanggalData)
                    ->where('transaksi_detail.kriteria_buah_id', $kriteriaBuahIdData)
                    ->groupBy('tanggal')
                    ->get()->first();
                if ($totalData) {
                    $total = $totalData->total;
                } else {
                    $total = 0;
                }

                $laporan[$no][$kriteriaBuahNameData] = $total;

                if (empty($laporanTotal['total' . $kriteriaBuahNameData])) {
                    $laporanTotal['total' . $kriteriaBuahNameData] = 0;
                }

                $laporanTotal['total' . $kriteriaBuahNameData] += $total;

                $no2++;
            }

            $no++;
        }

        $this->laporan = $laporan;
        $this->laporanTotal = $laporanTotal;

        // dd($totalData);
        // dd($laporan);
        // dd($laporanTotal);
    }

    /**
     * render view page.
     *
     * @return View
     */
    public function render(): View
    {
        abort_if(Gate::denies('laporanTransaksiAccess'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('livewire.laporan-transaksi.index')
            ->layoutData(['title' =>  $this->titleLayout]);
    }
}
