@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <div class="inline-block">
                    <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Payment Submitted</h1>
            <p class="text-gray-600 mb-4">
                Your payment has been submitted successfully. We're processing your request and will redirect you shortly.
            </p>
            <p class="text-sm text-gray-500 mb-6">
                Redirecting to {{ $isOrderPayment ? 'your orders' : 'dashboard' }}...
            </p>
            <a href="{{ route($redirectTo) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Click here if not redirected automatically
            </a>
        </div>
    </div>
</div>

<script>
    // Auto-redirect after 3 seconds
    setTimeout(() => {
        window.location.href = '{{ route($redirectTo) }}';
    }, 3000);
</script>
@endsection
