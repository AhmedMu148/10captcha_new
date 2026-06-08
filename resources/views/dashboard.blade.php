<x-app-layout>

    {{-- Welcome / Balance Header --}}
    <div class="text-center mb-3 mt-10 px-4">

        @php
            $displayName = ($userDetail && ($userDetail->fname || $userDetail->lname))
                ? trim($userDetail->fname . ' ' . $userDetail->lname)
                : $user->name;
        @endphp
        <div class="text-xl mb-1">Welcome <b>{{ $displayName }}</b></div>

        <div class="text-base mt-2 text-gray-600">
            <i class="las la-envelope mr-1"></i>{{ $user->email }}
        </div>

        <div class="my-4 flex items-center justify-center gap-3">
            <span class="font-semibold text-gray-700">Your Balance</span>
            <span class="bg-gray-800 text-white rounded px-4 py-1 text-sm font-semibold">
                ${{ number_format($user->balance_5d / 100000, 2) }}
            </span>
                <button type="button"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm rounded px-4 py-1.5 font-semibold transition"
                    onclick="Livewire.dispatch('open-add-fund-modal', { amount: 0, reason: '' })">
                    Add Funds
                </button>
                <span class="relative cursor-pointer text-gray-400 hover:text-gray-600"
                  x-data="{
                      show: false,
                      timer: null,
                      showFor5() {
                          this.show = true;
                          clearTimeout(this.timer);
                          this.timer = setTimeout(() => { this.show = false; }, 5000);
                      }
                  }"
                  x-init="showFor5()"
                  @click="showFor5()">
                <i class="las la-info-circle text-xl"></i>
                <div x-show="show" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute left-full top-1/2 -translate-y-1/2 ml-3 bg-white text-gray-700 text-sm rounded-lg border border-gray-200 shadow-lg z-50 text-left overflow-hidden" style="width:320px">
                    <div class="bg-gray-50 border-b border-gray-200 px-4 py-2 font-semibold text-gray-800 text-sm">Notice</div>
                    <div class="px-4 py-3 leading-relaxed text-gray-600 text-xs">
                        For Best Performance, Try To Keep your 10Captcha balance above <b class="text-gray-800">$1</b>. Service remains active below this, but Fair Rate may decrease. Service may be interrupted if balance falls below <b class="text-gray-800">$0.25</b>.
                    </div>
                    {{-- Arrow pointing left toward the icon --}}
                    <div class="absolute right-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-[6px] border-y-transparent border-r-[7px] border-r-gray-200"></div>
                    <div class="absolute right-full top-1/2 -translate-y-1/2 translate-x-[1px] w-0 h-0 border-y-[5px] border-y-transparent border-r-[6px] border-r-white"></div>
                </div>
            </span>
        </div>

    </div>

    {{-- Getting Started --}}
    <section class="px-4 mt-4 mb-6 max-w-screen-xl mx-auto">
        <h5 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i class="las la-dot-circle text-lg"></i> Getting Started
        </h5>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- 1. Add Funds --}}
            <div class="bg-white p-5 rounded border border-gray-200 shadow-sm">
                <h5 class="font-semibold text-base mb-2">1. Add funds</h5>
                <p class="text-green-700 text-sm mt-2">
                    Get the lowest price on the market for our automated recognition service - it's 3x cheaper than other competitors
                </p>
                <div class="mt-4 flex gap-2">
                    <button type="button"
                        class="bg-green-600 hover:bg-green-700 text-white text-sm rounded px-4 py-1.5 font-medium transition"
                        onclick="Livewire.dispatch('open-add-fund-modal', { amount: 0, reason: '' })">
                        Add Funds
                    </button>
                    <a href="{{ url('/pricing') }}" class="bg-gray-800 hover:bg-gray-900 text-white text-sm rounded px-4 py-1.5 font-medium transition">Pricing</a>
                </div>
            </div>

            {{-- 2. API Key --}}
            <div class="bg-white p-5 rounded border border-gray-200 shadow-sm">
                <h5 class="font-semibold text-base mb-3">2. API key</h5>
                <div class="text-sm mb-3 flex gap-4">
                    <a href="{{ url('/api') }}" target="_blank" class="text-green-600 hover:underline">How to use my key?</a>
                    <a href="{{ url('/api-docs') }}" target="_blank" class="text-blue-500 hover:underline text-xs self-center"><u>View API documentation</u></a>
                </div>
                @if($user->api_key)
                    <div class="flex items-center gap-2 mb-3">
                        <input type="text" id="apiKeyInput" readonly value="{{ $user->api_key }}"
                               class="bg-gray-100 text-xs px-2 py-1.5 rounded flex-1 border border-gray-200 truncate" />
                        <button onclick="copyApiKey()" class="bg-gray-800 hover:bg-gray-900 text-white text-xs rounded px-3 py-1.5 transition whitespace-nowrap">Copy</button>
                    </div>
                    <form method="POST" action="{{ route('api.regenerate') }}">
                        @csrf
                        <button type="submit" class="inline-block bg-green-600 hover:bg-green-700 text-white text-sm rounded px-4 py-1.5 font-medium transition">Regenerate API key</button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">No API key yet.
                        <form method="POST" action="{{ route('api.regenerate') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:underline bg-transparent border-none p-0 cursor-pointer">Generate one</button>
                        </form>.
                    </p>
                @endif
            </div>

            {{-- Solved Captcha --}}
            <div class="bg-white p-5 rounded border border-gray-200 shadow-sm">
                <h5 class="font-semibold text-base text-center mb-5">Solved Captcha</h5>
                <div class="flex justify-between items-center mb-5">
                    <span class="text-3xl font-bold">{{ $totalSolved }}</span>
                    <span class="text-gray-400 text-sm">Total Solved</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-3xl font-bold">{{ $solvedToday }}</span>
                    <span class="text-gray-400 text-sm">Solved Today</span>
                </div>
            </div>

        </div>
    </section>

    {{-- Quick Links + Reports --}}
    <section class="pb-10 max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Quick Links --}}
            <div class="bg-white border border-gray-200 shadow-sm rounded p-5">
                <div class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="las la-link text-lg"></i> Quick Links
                </div>
                <hr class="mb-3">
                <div class="my-3"><a href="{{ url('/') }}" class="flex items-center gap-2 text-gray-700 hover:text-green-600 transition text-sm"><i class="las la-home text-xl"></i> Home</a></div>
                <div class="my-3"><a href="{{ url('/api') }}" class="flex items-center gap-2 text-gray-700 hover:text-green-600 transition text-sm"><i class="las la-code text-xl"></i> API KEY</a></div>
                <div class="my-3"><a href="{{ route('ticket.sso.redirect') }}" class="flex items-center gap-2 text-gray-700 hover:text-green-600 transition text-sm"><i class="las la-ticket-alt text-xl"></i> Tickets</a></div>
                <div class="my-3"><a href="{{ url('/reports') }}" class="flex items-center gap-2 text-gray-700 hover:text-green-600 transition text-sm"><i class="las la-list text-xl"></i> Reports</a></div>
                <div class="my-3"><a href="{{ url('/payments') }}" class="flex items-center gap-2 text-gray-700 hover:text-green-600 transition text-sm"><i class="las la-comment-dollar text-xl"></i> Payments History</a></div>
                <div class="my-3"><a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-gray-700 hover:text-green-600 transition text-sm"><i class="las la-users-cog text-xl"></i> Profile</a></div>
                <div class="my-3"><a href="{{ url('/faq') }}" class="flex items-center gap-2 text-gray-700 hover:text-green-600 transition text-sm"><i class="las la-question-circle text-xl"></i> FAQ</a></div>
                <div class="my-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-gray-700 hover:text-green-600 transition text-sm">
                            <i class="las la-sign-out-alt text-xl"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Today's Reports --}}
            <div class="bg-white border border-gray-200 shadow-sm rounded p-5">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="las la-clipboard text-lg"></i> Today's reports
                    </span>
                    <a href="{{ url('/reports') }}" class="border border-green-600 text-green-600 hover:bg-green-600 hover:text-white text-xs rounded px-3 py-1.5 transition">Show All Reports</a>
                </div>
                <hr class="mb-3">
                <p class="text-center text-red-400 text-sm py-6">No Recent Reports</p>
            </div>

        </div>
    </section>

    <livewire:add-fund-modal />

    @push('scripts')
    <script>
    function copyApiKey() {
        const input = document.getElementById('apiKeyInput');
        input.select();
        navigator.clipboard.writeText(input.value).catch(() => { document.execCommand('copy'); });
    }
    </script>
    @endpush

</x-app-layout>
