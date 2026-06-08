<div>
  <x-modal name="add-fund-modal" maxWidth="md">
    <div class="p-6">
      <div class="mb-5 flex items-center justify-between gap-4">
        <div>
          <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900">
            <i class="las la-wallet text-xl text-green-600"></i>
            Add Funds
          </h2>
          <div class="mt-3 h-0.5 w-12 bg-green-600"></div>
        </div>

        <button type="button"
          class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
          wire:click="closeModal"
          wire:loading.attr="disabled"
          wire:target="processTopUp"
          @disabled($isProcessing)
          aria-label="Close">
          <i class="las la-times text-xl"></i>
        </button>
      </div>

      @if($reason)
      <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
        <div class="flex gap-2">
          <i class="las la-exclamation-triangle mt-0.5 text-base"></i>
          <span>{{ $reason }}</span>
        </div>
      </div>
      @endif

      @if($errorMessage)
      <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <div class="flex gap-2">
          <i class="las la-times-circle mt-0.5 text-base"></i>
          <span>{{ $errorMessage }}</span>
        </div>
      </div>
      @endif

      <div class="mb-5 rounded-lg border border-gray-200 bg-gray-50 p-4">
        <div class="flex items-center justify-between gap-4">
          <span class="text-sm text-gray-600">Current Balance</span>
          <span class="text-sm font-semibold text-gray-900">
            ${{ number_format(((float) (auth()->user()?->balance_5d ?? 0)) / 100000, 2) }}
          </span>
        </div>
        @if($requiredAmount > 0)
        <div class="mt-3 flex items-center justify-between gap-4 border-t border-gray-200 pt-3">
          <span class="text-sm text-gray-600">Amount Needed</span>
          <span class="text-sm font-semibold text-red-600">${{ number_format($requiredAmount, 2) }}</span>
        </div>
        @endif
      </div>

      <div class="mb-5">
        <label class="mb-2 block text-sm font-semibold text-gray-900">Quick Select</label>
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
          @foreach($presets as $preset)
          <button type="button"
            class="rounded-lg border px-3 py-2 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-60 {{ $amount == $preset ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-300 bg-white text-gray-700 hover:border-green-600 hover:text-green-700' }}"
            wire:click="setPreset({{ $preset }})"
            wire:loading.attr="disabled"
            wire:target="setPreset, processTopUp"
            @disabled($isProcessing)>
            ${{ $preset }}
          </button>
          @endforeach
        </div>
      </div>

      <div class="mb-5">
        <label for="topupAmount" class="mb-2 block text-sm font-semibold text-gray-900">Amount (USD)</label>
        <div class="flex overflow-hidden rounded-lg border border-gray-300 bg-white focus-within:border-green-600 focus-within:ring-2 focus-within:ring-green-100">
          <span class="flex items-center border-r border-gray-200 bg-gray-50 px-3 text-sm font-semibold text-gray-500">$</span>
          <input type="number"
            id="topupAmount"
            step="0.01"
            min="1"
            class="block w-full border-0 px-3 py-2.5 text-sm text-gray-900 focus:ring-0 disabled:bg-gray-100"
            wire:model.live.debounce.500ms="amount"
            placeholder="Enter amount"
            wire:loading.attr="disabled"
            wire:target="processTopUp"
            @disabled($isProcessing)>
        </div>
        <p class="mt-2 text-xs text-gray-500">Minimum: $1.00</p>
      </div>

      <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <button type="button"
          class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-60"
          wire:click="closeModal"
          wire:loading.attr="disabled"
          wire:target="processTopUp"
          @disabled($isProcessing)>
          Cancel
        </button>

        <button type="button"
          class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700 disabled:cursor-not-allowed disabled:bg-green-300"
          wire:click="processTopUp"
          wire:loading.attr="disabled"
          wire:target="processTopUp"
          @disabled($isProcessing || (float) $amount < 1)>
          <span wire:loading.remove wire:target="processTopUp">
            <i class="las la-credit-card mr-2"></i>Proceed to Checkout
          </span>
          <span wire:loading wire:target="processTopUp" class="inline-flex items-center">
            <i class="las la-spinner mr-2 animate-spin"></i>Processing...
          </span>
        </button>
      </div>
    </div>
  </x-modal>

  {{-- JavaScript for redirect --}}
  <script>
    document.addEventListener('livewire:initialized', () => {
      Livewire.on('redirect-to-payment', (event) => {
        window.location.href = event.url;
      });
    });
  </script>
</div>
