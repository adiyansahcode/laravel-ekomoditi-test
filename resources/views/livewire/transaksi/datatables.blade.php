<x-livewire-tables::table.cell>
  <span class=''>
    {{ $row->no_trans }}
  </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  <span class=''>
    {{ ($row->tanggal) ? ($row->tanggal->isoFormat('D MMMM YYYY')) : null }}
  </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  <span class=''>
    {{ $row->divisi }}
  </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  <span class=''>
    {{ $row->total_buah }}
  </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  <div class='text-sm text-gray-900'>At: {{ ($row->updated_at) ? ($row->updated_at->isoFormat('D MMMM YYYY')) : null }}</div>
  <div class='text-sm text-gray-500'>By: {{ $row->updatedBy->username }}</div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  <div class="flex item-center">
    @can('transaksiDetail')
    <div class="w-5 mr-3 transform text-gray-600 hover:text-jets-600 hover:scale-110">
      <button wire:click="$emit('detail', {{ $row->id }})">
        <x-heroicon-o-eye />
      </button>
    </div>
    @endcan
    @can('transaksiUpdate')
    <div class="w-5 mr-3 transform text-gray-600 hover:text-jets-600 hover:scale-110">
      <button wire:click="$emit('update', {{ $row->id }})">
        <x-heroicon-o-pencil />
      </button>
    </div>
    @endcan
    @can('transaksiDelete')
    <div class="w-5 mr-3 transform text-gray-600 hover:text-red-600 hover:scale-110">
      <button wire:click="$emit('delete', {{ $row->id }})">
        <x-heroicon-o-trash />
      </button>
    </div>
    @endcan
  </div>
</x-livewire-tables::table.cell>
