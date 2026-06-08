@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-end mb-6">
    <a href="{{ route('admin.categories.index') }}"
       class="text-sm text-indigo-600 hover:underline">
        ← Back to Categories
    </a>
</div>

{{-- Form Card --}}
<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required/>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Parent Category --}}
        <div class="mb-4">
            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
            <select name="parent_id" id="parent_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">No Parent</option>
                @foreach($parentCategories as $category)
                    <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-indigo-600 rounded"/>
                <span class="text-sm text-gray-700">Active</span>
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
            Create Category
        </button>
    </form>
</div>

@endsection