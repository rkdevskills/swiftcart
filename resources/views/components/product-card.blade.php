@props(['product'])

<a href="{{ route('products.show', $product->slug) }}"
   class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group">

    {{-- Image --}}
    <div class="aspect-square overflow-hidden bg-gray-100">
        @if($product->primaryImage)
            <img
                src="{{ $product->primaryImage->url() }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
            />
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300 text-5xl">🖼️</div>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-4">
        <p class="text-xs text-indigo-500 font-medium mb-1">{{ $product->category->name }}</p>
        <h3 class="font-semibold text-gray-800 text-sm leading-snug mb-2 line-clamp-2">{{ $product->name }}</h3>

        <div class="flex items-center justify-between">
            <span class="text-indigo-600 font-bold">RM {{ number_format($product->price, 2) }}</span>

            @if($product->reviews->count() > 0)
                <span class="text-xs text-gray-400">
                    ⭐ {{ $product->averageRating() }} ({{ $product->reviews->count() }})
                </span>
            @endif
        </div>
    </div>
</a>