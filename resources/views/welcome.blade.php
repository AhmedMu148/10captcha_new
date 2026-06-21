<x-app-layout>

    {{-- ===== Hero Section ===== --}}
    <section class="bg-white py-10 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h1 class="text-5xl font-extrabold text-gray-900 mb-4">
                        <span class="text-green-600">10</span>Captcha
                    </h1>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                        Automate Captcha Solving with <span class="text-green-600">10</span>Captcha –
                        Faster, Cheaper and More Accurate!
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        <span class="text-green-600">10</span>Captcha is a captcha solver service with high accuracy and
                        budget savings.
                        It recognizes reCAPTCHA, Captcha and other types of CAPTCHAs automatically.
                        The price for the service will always be at least 2 times cheaper than manual recognition
                        services
                        and up to 30 times faster than them.
                    </p>
                    @guest
                        <button onclick="Alpine.store('auth').open('login')"
                            class="inline-block px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition mr-3">
                            Get started
                        </button>
                        <button onclick="Alpine.store('auth').open('login')"
                            class="inline-block text-green-600 font-medium hover:underline">
                            $1 Free Trial
                        </button>
                    @else
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition mr-3">
                            Dashboard
                        </a>
                        @if (!auth()->user()->balance_5d)
                            <a href="{{ url('/tickets/new') }}"
                                class="inline-block text-green-600 font-medium hover:underline">
                                $1 Free Trial
                            </a>
                        @endif
                    @endguest
                </div>
                <div class="md:w-1/2 flex items-center justify-center">
                    <img src="{{ asset('assets/img/var.png') }}" class="w-full max-h-[420px] object-contain"
                        alt="hero image">
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Trust / Stats Section ===== --}}
    <section id="trust" class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="py-4">
                    <div class="text-4xl lg:text-3xl font-extrabold text-green-500">
                        <span class="count" data-target="7">0</span> sec
                    </div>
                    <div class="mt-3 text-gray-400 text-base">Average solving time</div>
                </div>
                <div class="py-4">
                    <div class="text-4xl lg:text-3xl font-extrabold text-green-500">
                        +<span class="count" data-target="6">0</span> types
                    </div>
                    <div class="mt-3 text-gray-400 text-base">Of Captchas supported</div>
                </div>
                <div class="py-4">
                    <div class="text-4xl lg:text-3xl font-extrabold text-green-500">
                        <span class="count" data-target="97">0</span>% success
                    </div>
                    <div class="mt-3 text-gray-400 text-base">Captcha solving rate</div>
                </div>
                <div class="py-4">
                    <div class="text-4xl lg:text-3xl font-extrabold text-green-500">
                        <span class="count" data-target="3">0</span>x cheaper
                    </div>
                    <div class="mt-3 text-gray-400 text-base">Than other recognition services</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Features Section ===== --}}
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Why Choose <span class="text-green-600">10</span>Captcha?
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white shadow rounded-xl p-6">
                    <i class="las la-hand-holding-usd text-6xl text-green-600"></i>
                    <h5 class="font-bold text-gray-900 mt-4 mb-2">+3x cheaper than other recognition services</h5>
                    <p class="text-gray-500 text-sm">The process is fully automated to solve captchas, This is why we
                        guarantee the market's lowest price</p>
                </div>
                <div class="bg-white shadow rounded-xl p-6">
                    <i class="las la-clock text-6xl text-green-600"></i>
                    <h5 class="font-bold text-gray-900 mt-4 mb-2">Very Fast &amp; Accurate Solving</h5>
                    <p class="text-gray-500 text-sm"><span class="text-green-600">10</span>Captcha uses the latest AI
                        technology to automatically recognize captchas, making it up to 30x faster than average.</p>
                </div>
                <div class="bg-white shadow rounded-xl p-6">
                    <i class="las la-percent text-6xl text-green-600"></i>
                    <h5 class="font-bold text-gray-900 mt-4 mb-2">Competitive and Flexible Rates</h5>
                    <p class="text-gray-500 text-sm">Pay-per-captcha. Add minimum $1 and start to automatically
                        recognize all types of captchas with accuracy and speed.</p>
                </div>
                <div class="bg-white shadow rounded-xl p-6">
                    <i class="las la-compress-arrows-alt text-6xl text-green-600"></i>
                    <h5 class="font-bold text-gray-900 mt-4 mb-2">Compatibility</h5>
                    <p class="text-gray-500 text-sm">Our service API is easy to integrate with your favorite software.
                        Register and Implement our API to your software.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Use Cases Section ===== --}}
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12">
                <div class="md:w-5/12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Use Cases</h2>
                    <p class="text-gray-500 leading-relaxed">
                        10Captcha now offers image recognition services to customers utilising Artificial Intelligence
                        and Machine Learning. Their mission is to explore the potential of Artificial Intelligence
                        further
                        to expand the possibilities of technology-driven environments.
                    </p>
                </div>
                <div class="md:w-7/12">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach ([['img' => '1.png', 'label' => 'Web Testing'], ['img' => '2.png', 'label' => 'Social Media'], ['img' => '3.png', 'label' => 'Data Collection'], ['img' => '4.png', 'label' => 'Market Research'], ['img' => '6.png', 'label' => 'Online Shopping'], ['img' => '7.png', 'label' => 'Online Gaming'], ['img' => '5.png', 'label' => 'Seo'], ['img' => '8.png', 'label' => 'Financial Services']] as $useCase)
                            <div class="bg-white shadow rounded-xl text-center py-4 px-2">
                                <img src="{{ asset('assets/img/home/' . $useCase['img']) }}" class="w-16 mx-auto"
                                    alt="{{ $useCase['label'] }}">
                                <p class="mt-3 text-sm font-semibold text-gray-700">{{ $useCase['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== Pricing Section ===== --}}
    <section id="pricing" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-10">Pricing</h2>

            <div class="max-w-4xl mx-auto bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                {{-- Header --}}
                <div
                    style="display:grid;grid-template-columns:1fr 160px 160px 160px;background:#22c55e;color:#fff;font-weight:600;font-size:0.92rem;padding:10px 20px">
                    <div></div>
                    <div style="text-align:center">Price Per 1,000</div>
                    <div style="text-align:center">Success</div>
                    <div style="text-align:center">Solution Speed</div>
                </div>
                {{-- Rows --}}
                @foreach ($plans as $i => $plan)
                    <div
                        style="display:grid;grid-template-columns:1fr 160px 160px 160px;padding:14px 20px;background:{{ $i % 2 === 0 ? '#fff' : '#f9fafb' }};border-top:1px solid #e5e7eb;align-items:center">
                        <div style="display:flex;align-items:center;gap:10px">
                            <img src="{{ str_starts_with($plan->img, 'http://') || str_starts_with($plan->img, 'https://') ? $plan->img : asset('storage/' . $plan->img) }}"
                                style="width:28px;height:28px;object-fit:contain" alt="{{ $plan->name }}">
                            <span style="font-weight:500;color:#111">{{ $plan->name }}</span>
                        </div>
                        <div style="text-align:center;color:#16a34a;font-weight:700">
                            @if (is_numeric($plan->price))
                                ${{ $plan->price }}
                            @else
                                <span style="color:#dc2626">{{ $plan->price }}</span>
                            @endif
                        </div>
                        <div style="text-align:center;color:#374151">
                            @if ($plan->success > 0)
                                <span style="color:#16a34a">&#10003;</span> {{ $plan->success }}%
                            @endif
                        </div>
                        <div style="text-align:center;color:#374151">
                            @if ($plan->speed > 0)
                                &#x1F551; {{ $plan->speed }}s
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                @guest
                    <button onclick="Alpine.store('auth').open('login')"
                        class="inline-block px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                        Try Now
                    </button>
                @else
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- ===== Easy Integration Section ===== --}}
    <section class="py-12 bg-white text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Easy integration</h2>
            <p class="text-gray-500 mb-10 max-w-2xl mx-auto">
                Our services can be easily integrated with any programs and scripts which require captcha recognition.
                Use our application and enjoy easy integration without any programming skills.
            </p>
            <div class="flex flex-wrap justify-center items-center gap-4">
                @foreach ([['file' => '1.svg', 'alt' => 'We work with Bablo'], ['file' => '2.svg', 'alt' => 'We work with GSA'], ['file' => '3.svg', 'alt' => 'We work with Key Collector'], ['file' => '4.svg', 'alt' => 'We work with Ranker X'], ['file' => '5.svg', 'alt' => 'We work with SEO Autopilot'], ['file' => '6.svg', 'alt' => 'We work with XseoN']] as $integration)
                    <img src="{{ asset('assets/img/home/' . $integration['file']) }}"
                        alt="{{ $integration['alt'] }}" width="140" height="75"
                        class="grayscale hover:grayscale-0 transition">
                @endforeach
            </div>
            <p class="mt-6 text-gray-500 font-medium">
                <span class="text-lg font-bold text-gray-800">+</span> More than 300 software programs
            </p>
        </div>
    </section>

    {{-- ===== FAQ Section ===== --}}
    <section id="faq" class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-10">Frequently asked questions</h2>
            {{-- FAQ accordion --}}
            @if (isset($homeFaqs) && $homeFaqs->count())
                <div class="mb-8">
                    @foreach ($homeFaqs as $faq)
                        <div x-data="{ open: false }"
                            class="mb-6 bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-lg">
                            <button @click="open = !open"
                                class="w-full py-3 px-6 text-left font-semibold transition duration-300 focus:outline-none flex justify-between items-center"
                                :class="open ? 'text-green-600' : 'text-slate-800 hover:text-green-600'">
                                <span>{{ $faq->question }}</span>
                                <i class="las la-angle-down text-xl transition-transform duration-300"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="bg-white px-6 pb-6 pt-2 text-gray-600 text-sm leading-relaxed border-t border-gray-50">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="text-center {{ empty($faqItems ?? null) ? 'mt-0' : 'mt-8' }}">
                @guest
                    <button onclick="Alpine.store('auth').open('login')"
                        class="inline-block px-8 py-3 border-2 border-green-600 text-green-600 font-semibold rounded-lg hover:bg-green-600 hover:text-white transition">
                        Ask a Question
                    </button>
                @else
                    <a href="{{ url('/tickets/new') }}"
                        class="inline-block px-8 py-3 border-2 border-green-600 text-green-600 font-semibold rounded-lg hover:bg-green-600 hover:text-white transition">
                        Ask a Question
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- ===== Bottom CTA Section ===== --}}
    <section class="bg-green-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h3 class="text-2xl font-bold mb-8 max-w-2xl mx-auto">
                Start saving up to 90% of your budget on recognizing captchas with
                <span class="text-gray-900">10</span>Captcha
            </h3>
            <div class="max-w-xs mx-auto">
                @guest
                    <button onclick="Alpine.store('auth').open('login')"
                        class="block w-full py-4 bg-gray-900 text-white font-bold text-lg rounded-lg hover:bg-gray-800 transition">
                        Get Started Now
                    </button>
                @else
                    <a href="{{ url('/dashboard') }}"
                        class="block w-full py-4 bg-gray-900 text-white font-bold text-lg rounded-lg hover:bg-gray-800 transition">
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- ===== Counter Animation Script ===== --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.count').forEach(function(el) {
                    const target = parseInt(el.getAttribute('data-target') || 0);
                    const duration = 2000;
                    const steps = 60;
                    const stepTime = duration / steps;
                    const increment = target / steps;
                    let current = 0;

                    const timer = setInterval(function() {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        el.textContent = Math.ceil(current);
                    }, stepTime);
                });
            });
        </script>
    @endpush

</x-app-layout>
