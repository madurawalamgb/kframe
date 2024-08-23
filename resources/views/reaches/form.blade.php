<div class="mb-3">
    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $reach->name ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!$editable ?' disabled':''}}>
</div><div class="mb-3">
    <label for="count" class="block text-sm font-medium text-gray-700">Count</label>
    <input type="number" id="count" name="count" value="{{ old('count', $reach->count ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!$editable ?' disabled':''}}>
</div><div class="mb-3">
    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
    <textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $reach->description ?? '') }}</textarea>                
</div>