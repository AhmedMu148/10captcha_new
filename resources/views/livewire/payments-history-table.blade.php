    <div style="max-width:1000px;margin:2rem auto;padding:0 1rem">
        <div style="background:#fff;border-radius:0.5rem;box-shadow:0 1px 4px rgba(0,0,0,0.08);padding:2rem">

            {{-- Title --}}
            <h2 style="font-size:1.75rem;font-weight:700;color:#1a1a1a;margin-bottom:0.5rem">Payments</h2>
            <hr style="border:none;border-bottom:3px solid #16a34a;width:3rem;margin:0 0 1.5rem 0">

            {{-- Tabs --}}
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.5rem;border-bottom:1px solid #e5e7eb;padding-bottom:0.75rem">
                @foreach([
                    'completed'   => 'Completed',
                    'uncompleted' => 'Uncompleted',
                    'canceled'    => 'Canceled',
                    'all'         => 'All',
                ] as $key => $label)
                    <a href="{{ route('payments.history', ['status' => $key]) }}"
                       style="
                           text-decoration:none;
                           padding:0.25rem 0.1rem;
                           font-weight:{{ $filter === $key ? '700' : '400' }};
                           color:{{ $filter === $key ? '#1a1a1a' : '#6b7280' }};
                           border-bottom:{{ $filter === $key ? '2px solid #1a1a1a' : '2px solid transparent' }};
                           margin-bottom:-2px;
                       ">
                        {{ $label }}
                    </a>
                    @if($key !== 'all')
                        <span style="color:#d1d5db">|</span>
                    @endif
                @endforeach
            </div>

            {{-- Table --}}
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:2px solid #e5e7eb">
                        <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">#</th>
                        <th style="text-align:left;padding:10px 12px;font-weight:600;color:#2563eb">Date</th>
                        <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Method</th>
                        <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Transaction ID</th>
                        <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Amount</th>
                        <th style="text-align:left;padding:10px 12px;font-weight:600;color:#374151">Status</th>
                        <th style="text-align:left;padding:10px 12px;font-weight:600;color:#2563eb">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                    <tr style="border-bottom:1px solid #f3f4f6">
                        <td style="padding:12px 12px;color:#374151;font-weight:500">#{{ $payment->id }}</td>
                        <td style="padding:12px 12px;color:#6b7280">{{ $payment->created_at->format('M j') }}</td>
                        <td style="padding:12px 12px;color:#374151;font-weight:500">{{ $payment->method }}</td>
                        <td style="padding:12px 12px;color:#374151">{{ $payment->transaction_id ?? '—' }}</td>
                        <td style="padding:12px 12px;color:#374151">${{ number_format($payment->amount_5d / 100000, 2) }}</td>
                        <td style="padding:12px 12px">
                            <span style="background:{{ $payment->statusColor() }};color:#fff;font-size:0.8rem;font-weight:600;padding:3px 10px;border-radius:0.25rem">
                                {{ $payment->statusLabel() }}
                            </span>
                        </td>
                        <td style="padding:12px 12px;color:#6b7280">{{ $payment->created_at->format('M j') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:20px 12px;text-align:center;color:#9ca3af">No payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        <div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;margin-top:1rem;color:#374151;font-size:0.875rem">
            <div>
                Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} entries
            </div>

            {{ $payments->onEachSide(1)->links('vendor.pagination.pagination') }}
        </div>

        </div>
    </div>
