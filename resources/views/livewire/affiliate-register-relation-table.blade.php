<div class="w-full">
    <div
        style="background:#fff;border-radius:0.5rem;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:1.5rem 2rem;margin-bottom:2rem">
        <h2 class="text-lg font-semibold text-gray-900 tracking-wide uppercase mb-4">
            Reffered users
        </h2>
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-top:1px solid #e5e7eb;border-bottom:2px solid #e5e7eb">
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">User</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Status</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Total solved</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Today solved</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($affiliateRegisterRelation as $registerRelation)
                    <tr>
                        <td style="padding:10px 12px;color:#374151">
                            #{{ $registerRelation->user->id }}
                        </td>
                        <td style="padding:10px 12px;">{!! $registerRelation->user->status_badge !!}</td>
                        <td style="padding:10px 12px;color:#374151">
                            {{ $registerRelation->report->sum('count') }}
                        </td>
                        <td style="padding:10px 12px;color:#374151">
                            {{ $registerRelation->report->filter(fn($r) => $r->created_at && $r->created_at->isToday())->sum('count') }}
                        </td>
                        <td style="padding:10px 12px;color:#16a34a;font-weight:500">
                            {{ $registerRelation->created_at ? $registerRelation->created_at->format('M j') : 'N/A' }}
                        </td>
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
                Showing {{ $affiliateRegisterRelation->firstItem() ?? 0 }} to
                {{ $affiliateRegisterRelation->lastItem() ?? 0 }} of
                {{ $affiliateRegisterRelation->total() }} entries
            </div>

            {{ $affiliateRegisterRelation->onEachSide(1)->links('vendor.pagination.pagination') }}
        </div>
    </div>
</div>
