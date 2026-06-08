<x-app-layout :breadcrumbs="$breadcrumbs ?? null" breadcrumb-current="Payment Processing">
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Header with background -->
                <div class="bg-gradient-to-br from-green-50 to-blue-50 p-8 text-center">
                    <!-- Animated Success Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="relative inline-flex items-center justify-center">
                            <div class="absolute w-20 h-20 bg-green-100 rounded-full animate-pulse"></div>
                            <div class="relative w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        {{ ($isOrderPayment ?? false) ? 'Order Confirmed' : 'Payment Successful' }}
                    </h2>
                    <p class="text-sm text-gray-500 font-medium">{{ ($isOrderPayment ?? false) ? 'Order ID: ' : 'Transaction ID: ' }}#{{ uniqid() }}</p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Message -->
                    <p class="text-gray-700 text-center mb-6 leading-relaxed">
                        @if($isOrderPayment ?? false)
                            Thank you for your purchase! Your payment has been received successfully. Your order is now being activated and will appear in your orders shortly.
                        @else
                            Thank you! Your payment has been processed successfully. Your account balance will be updated automatically.
                        @endif
                    </p>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Processing Information</p>
                                <p class="text-xs text-gray-600 mt-1">This typically takes a few seconds to a few minutes. You can safely navigate away from this page.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-600">
                            Redirecting to {{ ($isOrderPayment ?? false) ? 'orders' : 'dashboard' }} in 
                            <span id="countdown" class="font-bold text-green-600">5</span> seconds...
                        </p>
                        <div class="mt-3 w-full bg-gray-200 rounded-full h-1 overflow-hidden">
                            <div id="progressBar" class="bg-green-500 h-1 animate-pulse" style="width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-3">
                        <a href="{{ route($redirectTo ?? 'dashboard') }}" class="w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            {{ ($isOrderPayment ?? false) ? 'View My Orders' : 'Return to Dashboard' }}
                        </a>
                        <a href="{{ route('payments.history') }}" class="w-full inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.447.894l1.973 1.973a1 1 0 001.414-1.414L11 9.236V6z" clip-rule="evenodd"></path>
                            </svg>
                            Payment History
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                    <p class="text-xs text-center text-gray-500">
                        Need help? <a href="{{ route('support') ?? '#' }}" class="text-green-600 hover:text-green-700 font-medium">Contact Support</a>
                    </p>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500 mb-3">Secured by</p>
                <div class="flex items-center justify-center gap-4 text-gray-400">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            let seconds = 5;
            const countdownEl = document.getElementById('countdown');
            const progressBar = document.getElementById('progressBar');
            const redirectUrl = "{{ route($redirectTo ?? 'dashboard') }}";
            const totalSeconds = 5;
            
            const timer = setInterval(function() {
                seconds--;
                
                // Update countdown
                if (countdownEl) {
                    countdownEl.textContent = seconds;
                }
                
                // Update progress bar
                if (progressBar) {
                    const percentage = (seconds / totalSeconds) * 100;
                    progressBar.style.width = percentage + '%';
                }
                
                // Redirect when countdown reaches 0
                if (seconds <= 0) {
                    clearInterval(timer);
                    window.location.href = redirectUrl;
                }
            }, 1000);
            
            // Allow user to click buttons to navigate immediately
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (e.currentTarget.href) {
                        clearInterval(timer);
                    }
                });
            });
        })();
    </script>
</x-app-layout>
