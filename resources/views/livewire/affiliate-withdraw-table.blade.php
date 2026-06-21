<div class="w-full">
    <div
        style="background:#fff;border-radius:0.5rem;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:1.5rem 2rem;margin-bottom:2rem">
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-top:1px solid #e5e7eb;border-bottom:2px solid #e5e7eb">
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Date</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Amount</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Method</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Txn ID</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($affiliateWithdraws as $withdraw)
                    <tr wire:key="affiliate-withdraw-{{ $withdraw->id }}" style="border-bottom:1px solid #f3f4f6">
                        <td style="padding:10px 12px;color:#16a34a;font-weight:500">{{ ($withdraw->created_at)?$withdraw->created_at->format('M j'):'N/A' }}</td>
                        <td style="padding:10px 12px;color:#374151">${{ number_format($withdraw->amount_5d / 100000, 2) }}</td>
                        <td style="padding:10px 12px;color:#374151">{{ $withdraw->method }}</td>
                        <td style="padding:10px 12px;color:#374151">{{ $withdraw->txn_id }}</td>
                        <td style="padding:10px 12px;color:#6b7280">{{ $withdraw->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:16px 12px;text-align:center;color:#9ca3af">No data
                            available in table</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 flex items-center justify-between gap-3 text-sm text-gray-900">
            <div>
                Showing {{ $affiliateWithdraws->firstItem() ?? 0 }} to {{ $affiliateWithdraws->lastItem() ?? 0 }} of {{ $affiliateWithdraws->total() }} entries
            </div>

            {{ $affiliateWithdraws->onEachSide(1)->links('vendor.pagination.pagination') }}
        </div>
    </div>
</div>
