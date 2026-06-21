<x-app-layout>
    <section>
        <div class="max-w-7xl mx-auto px-4 py-12">
            <h2 class="text-center text-3xl font-semibold mb-12">
                PARTNERSHIP
            </h2>
            @if ($affiliate && $affiliate->status == 'Awaiting')
                <!-- Pending State Card -->
                <div class="bg-white rounded-2xl border border-amber-100 shadow-xl p-8 max-w-2xl mx-auto text-center space-y-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-50 text-amber-500 relative">
                        <i class="la la-clock text-4xl leading-none"></i>
                        <span class="absolute top-0.5 right-0.5 flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500"></span>
                        </span>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-gray-900">Application Under Review</h3>
                        <p class="text-gray-500 text-sm sm:text-base leading-relaxed">
                            Thank you for your interest! Your partnership application has been submitted successfully and is currently under review by our team.
                        </p>
                    </div>
                    <div class="bg-amber-50/50 rounded-xl p-4 border border-amber-100/50 max-w-md mx-auto">
                        <p class="text-amber-800 text-xs sm:text-sm font-medium">
                            <i class="la la-info-circle mr-1 text-base"></i> Reviews typically take 24 to 48 hours. We appreciate your patience!
                        </p>
                    </div>
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-200 text-sm font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 transition duration-200 w-full sm:w-auto shadow-sm">
                            <i class="la la-home mr-2 text-lg"></i> Back to Home
                        </a>
                    </div>
                </div>

            @elseif ($affiliate && $affiliate->status == 'Unapprove')
                <!-- Rejected State Card -->
                <div class="bg-white rounded-2xl border border-red-100 shadow-xl p-8 max-w-2xl mx-auto text-center space-y-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-50 text-red-500">
                        <i class="la la-ban text-4xl leading-none"></i>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-gray-900">Application Declined</h3>
                        <p class="text-gray-500 text-sm sm:text-base leading-relaxed">
                            Unfortunately, your application for the 10Captcha Partnership Program has been declined at this time.
                        </p>
                    </div>
                    <div class="bg-red-50/50 rounded-xl p-4 border border-red-100/50 max-w-md mx-auto">
                        <p class="text-red-800 text-xs sm:text-sm font-medium">
                            If you would like to provide more details about how you plan to promote our services, please contact our support team.
                        </p>
                    </div>
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ url('/tickets/new') }}" class="inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-sm font-semibold rounded-lg text-white transition duration-200 w-full sm:w-auto shadow-md shadow-red-500/10">
                            <i class="la la-envelope mr-2 text-lg"></i> Contact Support
                        </a>
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-200 text-sm font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 transition duration-200 w-full sm:w-auto shadow-sm">
                            <i class="la la-home mr-2 text-lg"></i> Back to Home
                        </a>
                    </div>
                </div>

            @else
                <!-- Form State -->
                <div class="border border-gray-300 rounded-lg p-6 max-w-3xl mx-auto bg-white shadow-sm">
                    <p class="text-gray-600 mb-8 text-sm sm:text-base">
                        Fill out the following information correctly to apply for the Partnership Program.
                    </p>

                    <form action="{{ route('partnership.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <input type="text" name="f_name" placeholder="First Name" required
                                class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                        </div>

                        <div>
                            <input type="text" name="l_name" placeholder="Last Name" required
                                class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                        </div>

                        <div>
                            <input type="text" name="software_name" placeholder="Software Name (optional)"
                                class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                        </div>

                        <div>
                            <input type="url" name="software_link" placeholder="Software link (optional)"
                                class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200">
                        </div>

                        <div>
                            <label class="block text-gray-900 font-semibold mb-2">
                                Describe how you will promote 10Captcha
                            </label>
                            <textarea name="message" required rows="5"
                                class="w-full bg-white border border-gray-300 rounded px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 transition duration-200"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-md">
                                Request
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
