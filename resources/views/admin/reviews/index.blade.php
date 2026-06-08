@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')

    <div class="flex items-center justify-end mb-6">
        <span class="text-sm text-gray-400">{{ $reviews->total() }} pending</span>
    </div>

    @if($reviews->isEmpty())
        <p class="text-gray-400 text-sm">No reviews found.</p>
    @else
        <div class="bg-white rounded-xl shadow-sm p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b">
                        <th class="pb-3">Product</th>
                        <th class="pb-3">Reviewer</th>
                        <th class="pb-3">Rating</th>
                        <th class="pb-3">Comment</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($reviews as $review)
                        <tr>
                            <td class="py-3 font-medium">{{ $review->product ? $review->product->name : '-' }}</td>
                            <td class="py-3 text-gray-600">{{ $review->user ? $review->user->name : 'Guest' }}</td>
                            <td class="py-3 text-gray-600">{{ str_repeat('⭐', $review->rating) }}</td>
                            <td class="py-3 text-gray-600 w-1/2">{{ $review->body }}</td>
                            <td class="py-3 text-gray-400">{{ $review->created_at->format('M d, Y') }}</td>
                            <td class="py-3">                             
                                <div class="flex items-center gap-2">
                                    @if($review->is_approved)
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Approved</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 bg-green-50 text-green-500 text-xs font-medium rounded-lg hover:bg-green-100 transition">
                                                ✅ Approve
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1 bg-red-50 text-red-500 text-xs font-medium rounded-lg hover:bg-red-100 transition"
                                                onclick="return confirm('Are you sure you want to delete this review?')">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    @endif

@endsection