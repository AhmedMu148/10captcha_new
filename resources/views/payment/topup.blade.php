<x-app-layout>
    <section>
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div style="display:flex;flex-wrap:wrap;background:#fff;border-radius:0.5rem;margin:0 0.25rem">

                {{-- col-lg-9: Image --}}
                <div style="flex:0 0 75%;max-width:75%">
                    <a href="#" onclick="Livewire.dispatch('open-add-fund-modal', { amount: 0, reason: '' })">
                        <img src="{{ asset('assets/img/funds.png') }}" alt="Make Payment" style="width:75%">
                    </a>
                </div>

                {{-- col-lg-3: Payment Info --}}
                <div style="flex:0 0 25%;max-width:25%;margin-top:3rem;padding:1rem;border-left:1px solid #e5e7eb">
                    <div style="margin-top:1rem">
                        <h3 style="font-size:1.5rem;font-weight:700">Make Payment</h3>
                    </div>

                    <div style="margin-bottom:0.5rem;margin-top:0.75rem">
                        You have
                        <span
                            style="display:inline-block;background:#6b7280;color:#fff;font-size:0.875rem;font-weight:600;padding:0.1rem 0.5rem;border-radius:0.25rem">
                            $ {{ number_format($user->balance_5d / 100000, 2) }}
                        </span>
                        in your balance.
                    </div>

                    <div style="margin-bottom:0.5rem">
                        Check
                        <a href="#" target="_blank" style="color:#3b82f6;font-style:italic">Payment History</a>
                    </div>

                    <div><small style="color:#9ca3af">* Pay and get Your Balance with PayPal</small></div>

                    <div style="text-align:center;margin-top:1rem">
                        @if (auth()->user())
                            <button type="button"
                                class="bg-green-600 hover:bg-green-700 text-white text-lg rounded px-4 py-1.5 font-semibold transition"
                                onclick="Livewire.dispatch('open-add-fund-modal', { amount: 0, reason: '' })">
                                Top Up
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-green-600 hover:bg-green-700 text-white text-lg rounded px-4 py-1.5 font-semibold transition">
                                Top Up
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>
    <livewire:add-fund-modal />
</x-app-layout>
