@if($detailModal)
<x-jet-dialog-modal wire:model="detailModal" maxWidth="lg">
  <x-slot name="title">
    <div class="text-gray-600">
      {{ $titleLayout }} Detail
    </div>
  </x-slot>

  <x-slot name="content">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
      <tbody class="bg-white divide-y divide-gray-200 text-gray-500">
        <tr>
          <td class="px-2 py-2 font-semibold capitalize">No Transaksi</td>
          <td class="">:</td>
          <td class="px-2 py-2 capitalize">{{ $noTrans }}</td>
        </tr>
        <tr>
          <td class="px-2 py-2 font-semibold capitalize">Tanggal</td>
          <td class="">:</td>
          <td class="px-2 py-2 capitalize">{{ $tanggal }}</td>
        </tr>
        <tr>
          <td class="px-2 py-2 font-semibold capitalize">Divisi</td>
          <td class="">:</td>
          <td class="px-2 py-2 capitalize">{{ $divisi }}</td>
        </tr>
        <tr>
          <td class="px-2 py-2 font-semibold capitalize">Total</td>
          <td class="">:</td>
          <td class="px-2 py-2 capitalize">{{ $totalBuah }}</td>
        </tr>
      </tbody>
    </table>

    <div class="mt-4">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-center text-sm font-medium text-gray-700 capitalize tracking-wider">
              No
            </th>
            <th scope="col" class="px-6 py-3 text-center text-sm font-medium text-gray-700 capitalize tracking-wider">
              Kriteria Buah
            </th>
            <th scope="col" class="px-6 py-3 text-center text-sm font-medium text-gray-700 capitalize tracking-wider">
              Jumlah
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($kriteriaBuah as $kriteriaBuahName => $kriteriaBuahId)
            @if($jumlah[$kriteriaBuahId] > 0)
              <tr>
                <td class="px-6 py-3 text-center text-sm font-medium text-gray-700 capitalize tracking-wider">
                  {{ $loop->iteration }}
                </td>
                <td class="px-6 py-3 text-left text-sm font-medium text-gray-700 capitalize tracking-wider">
                  {{ $kriteriaBuahName }}
                </td>
                <td class="px-6 py-3 text-right text-sm font-medium text-gray-700 capitalize tracking-wider">
                  {{ $jumlah[$kriteriaBuahId] }}
                </td>
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </x-slot>

  <x-slot name="footer">
    <x-jet-secondary-button wire:click="$toggle('detailModal')" wire:loading.attr="disabled">
      {{ __('Close') }}
    </x-jet-secondary-button>
  </x-slot>
</x-jet-dialog-modal>
@endif
