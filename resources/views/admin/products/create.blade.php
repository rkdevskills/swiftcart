@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-end mb-6">
    <a href="{{ route('admin.products.index') }}"
       class="text-sm text-indigo-600 hover:underline">
        ← Back to Products
    </a>
</div>

{{-- Form Card --}}
<div class="bg-white rounded-xl shadow-sm p-6 max-w-lg">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
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

        {{-- Category --}}
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category_id" id="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">Select a Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea id="description" name="description"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                      rows="3">{{ old('description') }}</textarea>
        </div>

        {{-- Price --}}
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
            <input type="number" id="price" name="price"
                   value="{{ old('price') }}" min="0"
                   step="0.01"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required/>
            @error('price')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Stock --}}
        <div class="mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
            <input type="number" id="stock" name="stock"
                   value="{{ old('stock') }}" min="0"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required/>
            @error('stock')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
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

        {{-- Image --}}
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
            <input type="file" id="image" name="image"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required/>
            @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
            Create Product
        </button>
    </form>
</div>

@endsection