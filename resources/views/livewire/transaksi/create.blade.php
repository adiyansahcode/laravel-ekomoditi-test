@if($createModal)
<x-jet-dialog-modal wire:model="createModal" maxWidth="2xl">
  <x-slot name="title">
    {{ $titleLayout }} Create Form
  </x-slot>

  <x-slot name="content">
    <div>
      <x-jet-label for="noTrans" value="no. Transaksi" class="capitalize" />
      @php
      $borderColor = $errors->has('noTrans')
      ? 'border-red-300 focus:border-red-300 focus:ring-red-300'
      : 'border-gray-300 focus:border-jets-300 focus:ring-jets-300';
      @endphp
      <x-input id="noTrans" name="noTrans" wire:model.defer="noTrans"
        class="mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 {{ $borderColor }}" />
      <x-error field="noTrans" class="mt-1 font-medium text-sm text-red-600" />
    </div>

    <div class="mt-4">
      <x-jet-label for="tanggal" value="tanggal" class="capitalize" />
      @php
      $borderColor = $errors->has('tanggal')
      ? 'border-red-300 focus:border-red-300 focus:ring-red-300'
      : 'border-gray-300 focus:border-jets-300 focus:ring-jets-300';
      @endphp
      <x-pikaday id="tanggal" name="tanggal" format="DD-MM-YYYY" wire:model.defer="tanggal"
        x-on:change="$wire.set('tanggal', $event.target.value)" :options="[
          'firstDay' => 0,
          'yearRange' => [2000,2030],
        ]" class="mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 {{ $borderColor }}" />
      <x-error field="tanggal" class="mt-1 font-medium text-sm text-red-600" />
    </div>

    <div class="mt-4">
      <x-jet-label for="divisi" value="divisi" class="capitalize" />
      @php
      $borderColor = $errors->has('divisi')
      ? 'border-red-300 focus:border-red-300 focus:ring-red-300'
      : 'border-gray-300 focus:border-jets-300 focus:ring-jets-300';
      @endphp
      <x-input id="divisi" name="divisi" wire:model.defer="divisi"
        class="mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 {{ $borderColor }}" />
      <x-error field="divisi" class="mt-1 font-medium text-sm text-red-600" />
    </div>

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
            <tr>
              <td class="px-6 py-3 text-center text-sm font-medium text-gray-700 capitalize tracking-wider">
                {{ $loop->iteration }}
              </td>
              <td class="px-6 py-3 text-left text-sm font-medium text-gray-700 capitalize tracking-wider">
                {{ $kriteriaBuahName }}
              </td>
              <td class="px-6 py-3 text-left">
                @php
                $borderColor = $errors->has('jumlah.' . $kriteriaBuahId)
                ? 'border-red-300 focus:border-red-300 focus:ring-red-300'
                : 'border-gray-300 focus:border-jets-300 focus:ring-jets-300';
                @endphp
                <x-input id="jumlah[{{$kriteriaBuahId}}]" name="jumlah[{{$kriteriaBuahId}}]" wire:model.defer="jumlah.{{$kriteriaBuahId}}"
                class="text-right mt-1 block w-full rounded-md shadow-sm focus:ring focus:ring-opacity-50 {{ $borderColor }}" />
                <x-error field="jumlah.{{$kriteriaBuahId}}" class="mt-1 font-medium text-sm text-red-600" />
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </x-slot>

  <x-slot name="footer">
    <x-jet-secondary-button wire:click="$toggle('createModal')" wire:loading.attr="disabled">
      {{ __('Cancel') }}
    </x-jet-secondary-button>

    <x-jet-button wire:click.prevent="store()" wire:loading.attr="disabled"
      class="items-center px-4 py-2 bg-jets-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-jets-700 active:bg-jets-900 focus:outline-none focus:border-jets-900 focus:ring focus:ring-jets-300 disabled:opacity-25 transition">
      {{ __('Save') }}
    </x-jet-button>
  </x-slot>
</x-jet-dialog-modal>
@endif
