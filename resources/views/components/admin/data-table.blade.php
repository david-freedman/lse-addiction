@props(['headers' => [], 'rows' => []])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-200 bg-white overflow-hidden']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rows as $row)
                    <tr class="hover:bg-gray-50">
                        @foreach($row as $cell)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {!! $cell !!}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-6 py-8 text-center text-sm text-gray-500">
                            Дані відсутні
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
