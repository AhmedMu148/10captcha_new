<div class="mx-auto max-w-7xl border border-gray-300 bg-white p-3">
    <div class="mb-5 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold leading-tight text-gray-900">Full list of custom image modules</h2>
            <div class="mt-2 h-px w-20 bg-gray-900"></div>
            <p class="mt-3 text-base text-gray-900">Complete/Updated list</p>
        </div>
        <a href="{{ route('custom-image.test') }}" class="mt-5 inline-flex shrink-0 items-center justify-center rounded bg-green-600 px-3 py-2 text-base font-bold leading-none text-white transition hover:bg-green-700">
            Test Image
        </a>
    </div>

    <div class="bg-gray-50 px-3 py-4">
        <div class="mb-4 flex items-center justify-between gap-4">
            <label class="flex items-center gap-2 text-sm text-gray-900">
                <span>Show</span>
                <select wire:model.live="perPage" class="h-8 rounded border-gray-300 py-0 pl-2 pr-7 text-sm focus:border-green-600 focus:ring-green-600">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entries</span>
            </label>

            <label class="flex items-center gap-2 text-sm text-gray-900">
                <span>Search:</span>
                <input type="search" wire:model.live.debounce.300ms="search" class="h-8 w-48 rounded border-gray-300 px-2 py-0 text-sm focus:border-green-600 focus:ring-green-600">
            </label>
        </div>

        <div wire:loading.class="opacity-60" wire:target="search,perPage,gotoPage,nextPage,previousPage" class="overflow-x-auto transition">
            <table class="min-w-full border-collapse text-left text-base">
                <thead>
                    <tr>
                        <th class="w-40 border-b border-gray-300 px-3 py-3 font-bold text-gray-900">Code</th>
                        <th class="w-48 border-b border-gray-300 px-3 py-3 font-bold text-gray-900">Name</th>
                        <th class="border-b border-gray-300 px-3 py-3 font-bold text-gray-900">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customImages as $customImage)
                        <tr wire:key="custom-image-{{ $customImage->id }}" class="hover:bg-white">
                            <td class="border-b border-gray-200 px-3 py-3 align-top">
                                <span class="inline-flex items-center bg-black px-2 py-1 text-sm font-bold leading-none text-white">
                                    {{ $customImage->code }}
                                </span>
                                @if ($customImage->code === 'common-3' || (int) $customImage->type === 2)
                                    <span class="ml-1 inline-flex items-center bg-red-600 px-2 py-1 text-sm font-bold leading-none text-white">BETA</span>
                                @endif
                            </td>
                            <td class="border-b border-gray-200 px-3 py-3 align-top font-medium text-gray-900">{{ $customImage->name }}</td>
                            <td class="border-b border-gray-200 px-3 py-3 align-top text-gray-900">{{ $customImage->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-8 text-center text-sm text-gray-500">No custom image modules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between gap-3 text-sm text-gray-900">
            <div>
                Showing {{ $customImages->firstItem() ?? 0 }} to {{ $customImages->lastItem() ?? 0 }} of {{ $customImages->total() }} entries
            </div>

            {{ $customImages->onEachSide(1)->links('vendor.pagination.pagination') }}
        </div>
    </div>
</div>
