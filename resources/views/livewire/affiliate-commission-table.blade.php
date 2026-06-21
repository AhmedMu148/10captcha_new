<div class="w-full">
    <div
        style="background:#fff;border-radius:0.5rem;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:1.5rem 2rem;margin-bottom:2rem">
        <h2 class="text-lg font-semibold text-gray-900 tracking-wide uppercase mb-4">
            Your earnings
        </h2>
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-top:1px solid #e5e7eb;border-bottom:2px solid #e5e7eb">
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">User</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Commission</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Percentage</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Status</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($affiliateCommissions as $commission)
                    <tr>
                        <td style="padding:10px 12px;color:#374151">#{{ $commission->affiliateRelation?->user?->id }}
                        </td>
                        <td style="padding:10px 12px;color:#374151">
                            ${{ number_format($commission->comm_amount_5d / 100000, 2) }}
                        </td>
                        <td style="padding:10px 12px;color:#374151">{{ $commission->comm_percent }}%</td>
                        <td style="padding:10px 12px;color:#6b7280">{{ $commission->status }}</td>
                        <td style="padding:10px 12px;color:#16a34a;font-weight:500">
                            {{ $commission->created_at ? $commission->created_at->format('M j') : 'N/A' }}</td>
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
                Showing {{ $affiliateCommissions->firstItem() ?? 0 }} to {{ $affiliateCommissions->lastItem() ?? 0 }} of
                {{ $affiliateCommissions->total() }} entries
            </div>

            {{ $affiliateCommissions->onEachSide(1)->links('vendor.pagination.pagination') }}
        </div>
    </div>
</div>
