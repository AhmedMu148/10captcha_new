@php
    $balanceRecord = \App\Models\AffiliateBalance::where('user_id', auth()->id())->first();
    $balance_5d = $balanceRecord ? $balanceRecord->balance_5d : 0;
    $comm_amount_5d = $balance_5d / 100000;
    $formatted = rtrim(rtrim(sprintf('%.10f', $comm_amount_5d), '0'), '.');
    if (empty($formatted) || $formatted === '0' || $formatted === '.') {
        $formatted = '0';
    }
@endphp

<div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Tabs/Links -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-sm font-medium">
            <a href="{{ route('partnership.register-relation') }}"
                class="px-3 py-2 rounded-lg transition duration-200 {{ $active === 'users' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Reffered users
            </a>

            <a href="{{ route('partnership') }}"
                class="px-3 py-2 rounded-lg transition duration-200 {{ $active === 'earnings' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Your earnings
            </a>

            <a href="{{ route('affiliate.withdraws') }}"
                class="px-3 py-2 rounded-lg transition duration-200 {{ $active === 'withdraw' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Withdraw
            </a>

            <a href="{{ route('partnership.option') }}"
                class="px-3 py-2 rounded-lg transition duration-200 {{ $active === 'option' ? 'bg-green-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Options
            </a>
        </div>

        <!-- Balance -->
        <div
            class="flex items-center gap-2 text-sm sm:text-base font-semibold text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm">
            <span>Your BALANCE:</span>
            <span class="text-green-600 text-lg font-bold">${{ $formatted }}</span>
        </div>
    </div>
</div>
