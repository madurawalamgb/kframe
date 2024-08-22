<div class="mb-3">
    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $customer->first_name ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!$editable ?' disabled':''}}>
</div><div class="mb-3">
    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $customer->last_name ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!$editable ?' disabled':''}}>
</div><div class="mb-3">
    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
    <input type="text" id="address" name="address" value="{{ old('address', $customer->address ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!$editable ?' disabled':''}}>
</div>