@extends('layouts.app')
@section('title', 'Testing Results - ' . config('app.name'))

@php
    $breadcrumbCurrent = "Custom Image Modules";
@endphp
@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<section class="py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 text-center mb-6">Custom Image Modules Captcha Testing</h1>
            <p class="text-gray-600 text-center mt-4 text-lg">
                Welcome to the Custom Image Modules Captcha Testing page! Here, you can evaluate the effectiveness of our custom modules for solving CAPTCHA challenges. Upload your CAPTCHA image and compare the output of each module to your known correct result.
            </p>
            <p class="text-center mt-4">Learn more about our <a href="{{ url('/custom-images') }}" class="text-gray-900 font-bold hover:text-blue-600 transition">Custom Image Modules</a></p>
        </div>
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-right mb-4">
                        <a href="{{ route('custom-image.test') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">
                           <i class="las la-arrow-left mr-2"></i>Test Another Image
                        </a>
                    </div>
                    
                    <h5 class="font-bold text-gray-900 mb-6 text-lg">Testing Results</h5>

                    @if ($hasProcessing)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="border-r border-gray-300 pr-6">
                                <div class="p-3">
                                    <p class="font-bold mb-4 text-gray-900">We are processing your request. Please Wait...</p>
                                    <div class="mb-3">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">Requests sent:</span>
                                            <span id="counter" class="font-bold text-blue-600">0</span>
                                        </div>
                                        <div class="flex justify-between mb-2">
                                            <span class="text-gray-600">Remaining:</span>
                                            <span id="remaining" class="font-bold text-yellow-600">-</span>
                                        </div>
                                        <div class="flex justify-between mb-3">
                                            <span class="text-gray-600">Current Module:</span>
                                            <span id="current-module" class="font-bold text-cyan-600">-</span>
                                        </div>
                                    </div>
                                    <div class="border-4 border-gray-300 border-t-blue-600 rounded-full w-10 h-10 animate-spin"></div>
                                </div>
                            </div>
                            <div class="lg:col-span-1">
                                <div class="p-3 text-center">
                                    <img src="{{ $base64String }}" class="w-full rounded h-64 object-contain p-3">
                                    <p class="mt-4">Known Correct Result: <span class="inline-block bg-gray-800 text-white px-3 py-2 rounded text-lg font-bold">{{ $records->first()->result }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 mt-6">
                            <div class="col-span-1 text-center">
                                <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-3">
                                    <i class="las la-info-circle mr-2"></i> Please don't close this page while processing.
                                </div>
                            </div>
                        </div>

                        <script>
                            let totalSent = 0;
                            let isProcessing = false;

                            function doSend() {
                                if (isProcessing) {
                                    return;
                                }

                                isProcessing = true;

                                $.ajax({
                                    url: '{{ route("custom-image.send-records") }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    dataType: 'json',
                                    success: function(result) {
                                        console.log('Response:', result);
                                        isProcessing = false;
                                        
                                        if (result && typeof result === 'object') {
                                            // Update counters LIVE
                                            if (result.processed) {
                                                totalSent += result.processed;
                                                $('#counter').html(totalSent);
                                            }
                                            
                                            $('#remaining').html(result.remaining || 0);
                                            
                                            // Show current module
                                            if (result.module) {
                                                $('#current-module').html(result.module);
                                            }

                                            // Check if completed
                                            if (result.status === 'completed' || result.remaining === 0) {
                                                console.log('Processing completed!');
                                                setTimeout(function() {
                                                    location.reload();
                                                }, 500);
                                            } else {
                                                // Continue processing immediately for live updates
                                                setTimeout(function() {
                                                    doSend();
                                                }, 500);
                                            }
                                        } else {
                                            console.log('Invalid response format');
                                            location.reload();
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Ajax error:', status, error);
                                        isProcessing = false;
                                        // On error, continue trying instead of reloading
                                        setTimeout(function() {
                                            doSend();
                                        }, 2000);
                                    }
                                });
                            }

                            $(document).ready(function() {
                                console.log('Starting live processing...');
                                doSend();
                            });
                        </script>
                    @else
                        <div class="grid grid-cols-1 lg:grid-cols-7 gap-6 mb-8 items-center">
                            <div class="lg:col-span-4 text-center border-r border-gray-300">
                                <img src="{{ $base64String }}" class="w-full rounded h-80 object-contain p-3">
                            </div>
                            <div class="lg:col-span-3 ps-0 lg:ps-8">
                                <h6 class="text-gray-600 text-xs font-bold uppercase mb-2">Original Target</h6>
                                <p class="text-2xl font-bold mb-4 text-gray-900">Known Correct Result</p>
                                <div class="bg-gray-50 p-6 rounded border border-gray-300 text-center">
                                    <span class="text-5xl font-bold text-gray-900">{{ $records->first()->result }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-300 bg-gray-50">
                                        <th class="font-bold text-gray-900 text-left px-4 py-3">Module</th>
                                        <th class="font-bold text-gray-900 text-left px-4 py-3">Result</th>
                                        <th class="font-bold text-gray-900 text-center px-4 py-3">Match</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                            <td class="px-4 py-3">
                                                <span class="font-bold text-gray-900">{{ $record->module }}</span>
                                            </td>
                                            <td class="text-gray-600 px-4 py-3">{{ $record->result_ocr ?? 'Pending...' }}</td>
                                            <td class="text-center px-4 py-3">
                                                @if ($record->result_ocr)
                                                    @php
                                                        $isMatch = ($record->result == $record->result_ocr || strcasecmp($record->result, $record->result_ocr) == 0);
                                                    @endphp
                                                    @if ($isMatch)
                                                        <span class="inline-block bg-green-100 text-green-700 px-3 py-2 rounded text-sm font-semibold">
                                                            <i class="las la-check-circle mr-1"></i> Match
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-red-100 text-red-700 px-3 py-2 rounded text-sm font-semibold">
                                                            <i class="las la-times-circle mr-1"></i> Mismatch
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-2 rounded text-sm font-semibold">
                                                        <i class="las la-hourglass-half mr-1"></i> Waiting
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-8 text-gray-600">No module records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
