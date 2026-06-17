@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">💳 Checkout</h1>

<form id="checkout-form" method="POST" action="{{ route('checkout.store') }}">
    @csrf
    <input type="hidden" name="payment_intent" id="payment-intent-id"/>

    <div class="grid md:grid-cols-3 gap-8">

        {{-- Left: Address + Payment --}}
        <div class="md:col-span-2 space-y-6">

            {{-- Delivery Address --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-bold text-gray-800 mb-4">📍 Delivery Address</h2>

                @if($addresses->isEmpty())
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500">No saved address. Please enter one below:</p>
                        <input type="text" name="line1" placeholder="Address Line 1"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"/>
                        <input type="text" name="city" placeholder="City"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"/>
                        <input type="text" name="postcode" placeholder="Postcode"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"/>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($addresses as $address)
                            <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:border-indigo-400 transition {{ $address->is_default ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200' }}">
                                <input type="radio"
                                       name="address_id"
                                       value="{{ $address->id }}"
                                       {{ $address->is_default ? 'checked' : '' }}
                                       class="mt-1"/>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $address->line1 }}</p>
                                    @if($address->line2)
                                        <p class="text-sm text-gray-500">{{ $address->line2 }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500">{{ $address->city }}, {{ $address->postcode }}</p>
                                    <p class="text-sm text-gray-500">{{ $address->country }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Payment --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-bold text-gray-800 mb-4">💳 Payment Details</h2>
                <div id="card-element" class="border border-gray-300 rounded-lg px-4 py-3"></div>
                <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                <p class="text-xs text-gray-400 mt-3">
                    🔒 Test card: <span class="font-mono font-bold">4242 4242 4242 4242</span> — any future date — any CVC
                </p>
            </div>
        </div>

        {{-- Right: Order Summary --}}
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <h2 class="font-bold text-gray-800 mb-4">Order Summary</h2>

                <div class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 line-clamp-1 flex-1 mr-2">
                                {{ $item->product->name }} x{{ $item->quantity }}
                            </span>
                            <span class="font-medium shrink-0">£ {{ number_format($item->subtotal(), 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>£ {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span class="text-green-600">Free</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t">
                        <span>Total</span>
                        <span class="text-indigo-600">£ {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <button type="submit" id="submit-btn"
                        class="mt-6 w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition disabled:opacity-50">
                    Pay £ {{ number_format($total, 2) }}
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe  = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    const card    = elements.create('card');
    card.mount('#card-element');

    card.on('change', ({ error }) => {
        document.getElementById('card-errors').textContent = error ? error.message : '';
    });

    document.getElementById('checkout-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.textContent = 'Processing...';

        const { paymentIntent, error } = await stripe.confirmCardPayment(
            '{{ $intent->client_secret }}',
            { payment_method: { card } }
        );

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            btn.disabled = false;
            btn.textContent = 'Pay £ {{ number_format($total, 2) }}';
        } else {
            document.getElementById('payment-intent-id').value = paymentIntent.id;
            e.target.submit();
        }
    });
</script>
@endpush
@endsection