<x-app-layout>
    <section>
        <div class="max-w-7xl mx-auto px-4 py-12">
            @include('affiliate.partials.top-menu', ['active' => 'withdraw'])

            <h2 class="text-center text-3xl font-semibold mb-12">
                Withdrawal Request
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- Table -->
                <div class="lg:col-span-8 col-span-12">
                    <livewire:affiliate-withdraw-table />
                </div>

                <!-- Form -->
                <div class="lg:col-span-4 col-span-12">
                    <div class="bg-white rounded shadow-lg p-6">

                        <form action="{{ route('withdraw.store') }}" method="POST">
                            @csrf
                            <div class="mb-8">
                                <label class="block mb-3 text-xl font-bold">
                                    Amount (in USD)
                                </label>

                                <div class="flex">
                                    <span
                                        class="flex items-center px-4 border border-r-0 border-gray-300 rounded-l-md bg-gray-50">
                                        $
                                    </span>

                                    <input type="number" name="amount" placeholder="Amount"
                                        class="w-full border border-gray-300 rounded-r-md px-4 py-3" required>
                                </div>
                            </div>

                            <div class="mb-8">
                                <label class="block mb-3 text-xl font-bold">
                                    Withdrawal Method
                                </label>

                                <select name="method" class="w-full border border-gray-300 rounded-md px-4 py-3"
                                    required>

                                    @foreach ($methods as $method)
                                        <option value="{{ $method }}">
                                            {{ $method }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-md">
                                Balance Withdrawal
                            </button>

                        </form>

                    </div>

                </div>
            </div>

            <div class="mt-12">
                <h3 class="text-2xl font-bold mb-4"> Notes & Details </h3>
                <ul class="text-gray-600 text-sm sm:text-base mt-4 list-disc list-inside">
                    <li>Minimum withdrawal request for Account Balance is <b>$1</b></li>
                    <li>Minimum withdrawal request is <b><u>$10</u></b> for <b><u>PayPal, Bitcoins, Neteller &
                                Skrill</u></b></li>
                    <li>Minimum withdrawal request is <b><u>$50</u></b> for <b><u>Payoneer</u></b></li>
                    <li>Withdrawal request is available only if you have no pending withdrawal requests.</li>
                </ul>
            </div>
        </div>
    </section>
</x-app-layout>
