<x-app-layout>
    <section>
        <div class="max-w-7xl mx-auto px-4 py-12">
            @include('affiliate.partials.top-menu', ['active' => 'option'])
            <h2 class="text-center text-3xl font-semibold mb-12">
                Payout Accounts
            </h2>
            <div class="border border-gray-300 rounded-lg p-6 max-w-3xl mx-auto">

                <form action="{{ route('option.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="text" name="id" value="{{ isset($affiliateOption) ? $affiliateOption->id : '0' }}"
                        hidden>
                    <input type="text" name="user_id"
                        value="{{ isset($affiliateOption) ? $affiliateOption->user->id : '0' }}" hidden>

                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">
                            PayPal account Email
                        </label>
                        <input type="text" name="paypal"
                            {{ isset($affiliateOption) && $affiliateOption->paypal != null ? 'value=' . $affiliateOption->paypal : '' }}
                            placeholder="PayPal account Email"
                            class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                    </div>

                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">
                            Payoneer account Email
                        </label>
                        <input type="text" name="payoneer"
                            {{ isset($affiliateOption) && $affiliateOption->payoneer != null ? 'value=' . $affiliateOption->payoneer : '' }}
                            placeholder="Payoneer account Email"
                            class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                    </div>

                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">
                            Bitcoin account Email
                        </label>
                        <input type="text" name="bitcoin"
                            {{ isset($affiliateOption) && $affiliateOption->bitcoin != null ? 'value=' . $affiliateOption->bitcoin : '' }}
                            placeholder="Bitcoin account Email"
                            class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                    </div>

                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">
                            Neteller account Email
                        </label>
                        <input type="text" name="neteller"
                            {{ isset($affiliateOption) && $affiliateOption->neteller != null ? 'value=' . $affiliateOption->neteller : '' }}
                            placeholder="Neteller account Email"
                            class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                    </div>

                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">
                            Skrill account Email
                        </label>
                        <input type="text" name="skrill"
                            {{ isset($affiliateOption) && $affiliateOption->skrill != null ? 'value=' . $affiliateOption->skrill : '' }}
                            placeholder="Skrill account Email"
                            class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-md">
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
