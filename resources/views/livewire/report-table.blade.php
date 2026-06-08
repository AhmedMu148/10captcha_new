<div style="max-width:900px;margin:2rem auto;padding:0 1rem">

    {{-- Today's Reports --}}
    <div
        style="background:#fff;border-radius:0.5rem;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:1.5rem 2rem;margin-bottom:2rem">
        <h2 style="font-size:1.5rem;font-weight:700;color:#1a1a1a;margin-bottom:0.5rem">Today's reports</h2>
        <hr style="border:none;border-bottom:3px solid #16a34a;width:3rem;margin:0 0 1.25rem 0">

        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-top:1px solid #e5e7eb;border-bottom:2px solid #e5e7eb">
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">#</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Name</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Price</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Count</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($todayReports as $report)
                    <tr wire:key="today-report-{{ $report->id }}" style="border-bottom:1px solid #f3f4f6">
                        <td style="padding:10px 12px;color:#6b7280"># {{ $report->id }}</td>
                        <td style="padding:10px 12px;color:#374151">
                            {{ \App\Http\Controllers\ReportController::typeName($report->type) }}</td>
                        <td style="padding:10px 12px;color:#374151">
                            ${{ number_format($report->price_5d / 100000, 4) }}</td>
                        <td style="padding:10px 12px;color:#374151">{{ $report->count }}</td>
                        <td style="padding:10px 12px;color:#16a34a;font-weight:500">
                            {{ ($report->created_at)?$report->created_at->format('M j'):'N/A' }}</td>
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
                Showing {{ $todayReports->firstItem() ?? 0 }} to {{ $todayReports->lastItem() ?? 0 }} of {{ $todayReports->total() }} entries
            </div>

            {{ $todayReports->onEachSide(1)->links('vendor.pagination.pagination') }}
        </div>
    </div>

    {{-- All Reports --}}
    <div
        style="background:#fff;border-radius:0.5rem;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:1.5rem 2rem;margin-bottom:2rem">
        <h2 style="font-size:1.5rem;font-weight:700;color:#1a1a1a;margin-bottom:0.25rem">All Reports</h2>
        <p style="font-size:0.875rem;color:#16a34a;margin-bottom:0.75rem">Reports is collect every 24 hours</p>
        <hr style="border:none;border-bottom:3px solid #16a34a;width:3rem;margin:0 0 1.25rem 0">

        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-top:1px solid #e5e7eb;border-bottom:2px solid #e5e7eb">
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">#</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Name</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Price</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Count</th>
                    <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($allReports as $report)
                    <tr wire:key="all-report-{{ $report->id }}" style="border-bottom:1px solid #f3f4f6">
                        <td style="padding:10px 12px;color:#6b7280"># {{ $report->id }}</td>
                        <td style="padding:10px 12px;color:#374151">
                            {{ \App\Http\Controllers\ReportController::typeName($report->type) }}</td>
                        <td style="padding:10px 12px;color:#374151">
                            ${{ number_format($report->price_5d / 100000, 4) }}</td>
                        <td style="padding:10px 12px;color:#374151">{{ $report->count }}</td>
                        <td style="padding:10px 12px;color:#16a34a;font-weight:500">
                            {{ ($report->created_at)?$report->created_at->format('M j'):'N/A' }}</td>
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
                Showing {{ $allReports->firstItem() ?? 0 }} to {{ $allReports->lastItem() ?? 0 }} of {{ $allReports->total() }} entries
            </div>

            {{ $allReports->onEachSide(1)->links('vendor.pagination.pagination') }}
        </div>

    </div>

</div>
