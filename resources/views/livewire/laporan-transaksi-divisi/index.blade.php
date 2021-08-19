<div>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-600 leading-tight uppercase">
      {{ $titleLayout }}
    </h2>
  </x-slot>

  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                    Divisi
                  </th>
                  <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                    Tanggal Panen
                  </th>
                  @foreach($kriteriaBuahName as $kriteriaBuahNameData => $kriteriaBuahIdData)
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                      {{ $kriteriaBuahNameData }}
                    </th>
                  @endforeach
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($laporan as $laporanData)
                  <tr>
                    <td class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                      {{ $laporanData['divisi'] }}
                    </td>
                    <td class="px-6 py-4 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                      {{ $laporanData['tanggal'] }}
                    </td>
                    @foreach($kriteriaBuahName as $kriteriaBuahNameData => $kriteriaBuahIdData)
                      <td class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                        {{ $laporanData[$kriteriaBuahNameData] }}
                      </td>
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
              <tfoot class="bg-gray-50">
                <tr>
                  <td scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                    Total
                  </td>
                  <td>&nbsp;</td>
                  @foreach($kriteriaBuahName as $kriteriaBuahNameData => $kriteriaBuahIdData)
                    <td scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                      {{ $laporanTotal['total' . $kriteriaBuahNameData] }}
                    </td>
                  @endforeach
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
