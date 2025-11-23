<x-layouts.app :title="'Transportation Management'">
    <div class="px-4 py-6 max-w-6xl mx-auto space-y-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Vehicle Form -->
        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md p-6 w-full">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Add New Vehicle</h2>
            <form action="{{ route('transports') }}" method="POST" class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @csrf

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Enter vehicle name" class="mt-1 w-full rounded-lg border px-4 py-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity') }}" required placeholder="Enter capacity" class="mt-1 w-full rounded-lg border px-4 py-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    @error('capacity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" rows="1" placeholder="Enter description" class="mt-1 w-full rounded-lg border px-4 py-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2 md:col-span-2">
                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition">Add Vehicle</button>
                </div>
            </form>
        </div>

        <!-- Vehicles Table -->
        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md p-6 overflow-x-auto">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Vehicle List</h2>
            <div class="w-full overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">#</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Name</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Capacity</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Description</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transport as $vehicle)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $vehicle->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $vehicle->capacity }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($vehicle->description, 50) }}</td>
                                <td class="px-4 py-2 text-center text-sm flex justify-center gap-2">
                                    <button onclick="editVehicle({{ $vehicle->id }}, '{{ addslashes($vehicle->name) }}', '{{ $vehicle->capacity }}', '{{ addslashes($vehicle->description) }}')" class="text-blue-600 hover:text-blue-700">Edit</button>
                                    <span class="mx-1 text-gray-700">|</span>
                                    <form action="{{ route('transports.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No vehicles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Edit Vehicle Modal -->
    <div id="editVehicleModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50 p-4">
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Edit Vehicle</h2>
            <form id="editVehicleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehicle Name</label>
                        <input type="text" id="edit_name" name="name" class="mt-1 w-full rounded-lg border px-4 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capacity</label>
                        <input type="number" id="edit_capacity" name="capacity" class="mt-1 w-full rounded-lg border px-4 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea id="edit_description" name="description" rows="3" class="mt-1 w-full rounded-lg border px-4 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"></textarea>
                    </div>
                    <div class="sm:col-span-2 flex justify-end gap-3 mt-4">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded-lg dark:border-gray-600 dark:text-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Vehicle</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editVehicle(id, name, capacity, description) {
            document.getElementById('editVehicleModal').classList.remove('hidden');
            document.getElementById('editVehicleModal').classList.add('flex');
            document.getElementById('editVehicleForm').action = '/transports/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_capacity').value = capacity;
            document.getElementById('edit_description').value = description || '';
        }
        function closeEditModal() {
            document.getElementById('editVehicleModal').classList.add('hidden');
            document.getElementById('editVehicleModal').classList.remove('flex');
            document.getElementById('editVehicleForm').reset();
        }
    </script>
</x-layouts.app>
