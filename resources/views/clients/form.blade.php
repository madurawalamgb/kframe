<div class="mb-3">
    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $client->name ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!$editable ?' disabled':''}}>
</div><div class="mb-3">
    <label for="qualification" class="block text-sm font-medium text-gray-700">Qualification</label>
    <textarea id="qualification" name="qualification" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!$editable ?' disabled':''}}>{{ old('qualification', $client->qualification ?? '') }}</textarea>                
</div><div class="mb-3">
    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
    <input type="number" id="age" name="age" value="{{ old('age', $client->age ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!$editable ?' disabled':''}}>
</div><div class="mb-3">
    <div class="flex items-center">
        <input type="checkbox" id="higher_than" name="higher_than" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="1" {{ old('higher_than', $client->higher_than ?? '') ? ' checked' : '' }} {{!$editable ?' disabled':''}}>
        <label for="higher_than" class="ml-2 block text-sm font-medium text-gray-700">Higher than 6 feet</label>
    </div>
</div><div class="mb-3">
    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
    <select id="gender" name="gender" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!$editable ?' disabled':''}}>
    @foreach(\App\Models\Client::getGender() as $k => $v) 
        <option value="{{$k}}"  {{ $k == (old('gender', $client->gender ?? '')) ? ' selected' : '' }} >{{$v}}</option>
    @endforeach
    </select>
</div>