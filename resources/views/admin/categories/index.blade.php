@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="flex items-center justify-end mb-6">
        <a href="{{ route('admin.categories.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + Add Category
        </a>
    </div>

    @if($categories->isEmpty())
        <p class="text-gray-400 text-sm">No categories found.</p>
    @else
        <div class="bg-white rounded-xl shadow-sm p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b">
                        <th class="pb-3">Name</th>
                        <th class="pb-3">Parent</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Created At</th>
                        <th class="pb-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $category)
                        <tr>
                            <td class="py-3 font-medium">{{ $category->name }}</td>
                            <td class="py-3 text-gray-600">{{ $category->parent ? $category->parent->name : '-' }}</td>
                            <td class="py-3">
                                @if($category->is_active)
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Inactive</span>
                                @endif
                            </td>                           
                            <td class="py-3 text-gray-400">{{ $category->created_at->format('M d, Y') }}</td>
                            <td class="py-3">

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-medium rounded-lg hover:bg-indigo-100 transition">
                                        ✏️ Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1 bg-red-50 text-red-500 text-xs font-medium rounded-lg hover:bg-red-100 transition"
                                                onclick="return confirm('Are you sure you want to delete this category?')">
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
            {{ $categories->links() }}
        </div>
    @endif  

@endsection