<x-app-layout>
    <section>
        <div class="max-w-7xl mx-auto px-4 py-12">
            @include('affiliate.partials.top-menu', ['active' => 'users'])
            <h2 class="text-center text-3xl font-semibold mb-12">
                PARTNERSHIP
            </h2>
            <p class="mb-2">You Affiliate Link: <span
                    class="text-blue-600 hover:underline bg-gray-100 px-2 py-1 cursor-pointer"
                    onclick="copyToClipboard('{{ $affiliate->promo_link }}')">{{ $affiliate->promo_link }}</span></p>
            <livewire:affiliate-register-relation-table />

            <ul class="text-gray-600 text-sm sm:text-base mt-4 list-disc list-inside">
                <li>Commesion is <b>10%</b></li>
                <li>Your will get Commesion for every consumption for the user for <b>3 months</b></li>
            </ul>

        </div>
    </section>

    <script>
        function copyToClipboard(text) {
            var textField = document.createElement('textarea');
            textField.innerText = text;
            document.body.appendChild(textField);
            textField.select();
            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'successful' : 'unsuccessful';
                console.log('Copying text command was ' + msg);
                alert('Text copied to clipboard!');
            } catch (err) {
                console.log('Oops, unable to copy');
                alert('Failed to copy text to clipboard!');
            }
            document.body.removeChild(textField);
        }
    </script>
</x-app-layout>
