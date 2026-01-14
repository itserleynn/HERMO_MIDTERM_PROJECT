<x-layouts.app :title="'Customer Management'">
    <div class="px-4 py-6 max-w-7xl mx-auto space-y-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        <!-- Top Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- Total Customers -->
            <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Customers</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $customer->count() }}</h3>
                </div>
                <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/40">
                    <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Total Transportation -->
            <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Transportation</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $transport->count() }}</h3>
                </div>
                <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/40">
                    <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 13h2l1-2h14l1 2h2M5 21h14a2 2 0 002-2V8H3v11a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Hard-coded Card -->
            <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">System Status</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">Active</h3>
                </div>
                <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/40">
                    <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Add New Customer Form -->
        <div class="bg-gray-900 text-gray-100 rounded-xl shadow-md p-6 w-full">
            <h2 class="text-lg font-semibold mb-4">Add New Customer</h2>
            <form action="{{ route('customers') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-3">
                @csrf
            
                <div>
                    <label class="block text-sm font-medium mb-1">Full Name</label>
                    <input type="text" name="full_name" required placeholder="Enter full name"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('full_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <div>
                    <label class="block text-sm font-medium mb-1">Location</label>
                    <input type="text" required name="location" placeholder="Enter location"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Transportion</label>
                    <select name="transport_id" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a Transportation</option>
                        @foreach($transport as $trans)
                            <option value="{{ $trans->id }}">{{ $trans->name }}</option>
                        @endforeach
                    </select>
                    @error('transport_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" required name="phone" placeholder="Enter phone"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="1" placeholder="Enter description"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="sm:col-span-1">
                    <label class="mb-1 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Customer Photo (Optional)
                    </label>
                    <input
                        type="file"
                        name="photo"
                        accept="image/jpeg,image/png,image/jpg"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-1.5 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-1 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:file:bg-blue-900/20 dark:file:text-blue-400"
                    >
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        JPG, PNG or JPEG. Max 2MB.
                    </p>
                    @error('photo')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            
                <div class="sm:col-span-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg text-white font-medium transition">Add Customer</button>
                </div>
            </form>
        </div>

        <!-- Search & Filter Section -->
        <div class="mb-10 rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">

            <!-- Header + Export -->
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                    Search & Filter Customers
                </h2>

                <form method="GET" action="{{ route('customers.export') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="transport_filter" value="{{ request('transport_filter') }}">

                    <button type="submit"
                        class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export PDF
                    </button>
                </form>
            </div>

            <!-- Filters -->
            <form action="{{ route('dashboard') }}" method="GET"
                class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <!-- Search -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Search Customer
                    </label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name or location"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm
                            focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                    >
                </div>

                <!-- Transportation Filter -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Filter by Transportation
                    </label>
                    <select
                        name="transport_filter"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm
                            focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                    >
                        <option value="">All Transportation</option>
                        @foreach($transport as $trans)
                            <option value="{{ $trans->id }}"
                                {{ request('transport_filter') == $trans->id ? 'selected' : '' }}>
                                {{ $trans->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                        Apply Filters
                    </button>

                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-lg border border-neutral-300 px-4 py-2 text-sm font-medium
                            text-neutral-700 transition hover:bg-neutral-100
                            dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                        Clear
                    </a>
                </div>

            </form>
        </div>


        <!-- Customer Table -->
        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md p-6 overflow-x-auto">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Customer List</h2>
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[600px] table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">#</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Photo</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Full Name</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Location</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Transportation</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Phone</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Description</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($customer as $cust)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                @if($cust->photo)
                                    <img
                                        src="{{ Storage::url($cust->photo) }}"
                                        alt="{{ $cust->full_name }}"
                                        class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-100 dark:ring-blue-900"
                                    >
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ strtoupper(substr(trim($cust->full_name), 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $cust->full_name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $cust->location}}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $cust->transport?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $cust->phone}}</td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($cust->description, 50) }}</td>
                            <td class="px-4 py-2 text-sm text-center flex flex-col sm:flex-row justify-center gap-2">
                                <button onclick="editCustomer(
                                    '{{ $cust->id }}',
                                    '{{ addslashes($cust->full_name) }}',
                                    '{{ addslashes($cust->location) }}',
                                    '{{ $cust->transport_id }}',
                                    '{{ addslashes($cust->phone) }}',
                                    '{{ addslashes($cust->description) }}',
                                    '{{ $cust->photo }}',
                                    );" class="text-blue-600 hover:text-blue-700">Edit</button>
                                <span class="mx-1 text-gray-700">|</span>
                                <form action="{{ route('customers.destroy', $cust->id) }}" method="POST" onsubmit="return confirm('Delete this customer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No customers found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Customer Modal -->
        <div id="editCustomerModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-[9999] px-4">
            <div class="w-full max-w-2xl rounded-xl bg-gray-900 p-6 shadow-lg">
                <h2 class="mb-4 text-lg font-semibold text-gray-100">Edit Customer</h2>

                <form id="editCustomerForm" method="POST" enctype="multipart/form-data"
                    class="grid gap-4 grid-cols-1 sm:grid-cols-2">
                    @csrf
                    @method('PUT')

                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" name="full_name" id="edit_full_name"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100">
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Location</label>
                        <input type="text" name="location" id="edit_location"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100">
                    </div>

                    <!-- Transportation -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Transportation</label>
                        <select name="transport_id" id="edit_transport_id"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100">
                            <option value="">Select transportation</option>
                            @foreach($transport as $trans)
                                <option value="{{ $trans->id }}">{{ $trans->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Phone</label>
                        <input type="text" name="phone" id="edit_phone"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100">
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2 text-gray-100"></textarea>
                    </div>

                    <!-- Current Photo Preview -->
                    <div id="currentPhotoPreview" class="sm:col-span-2"></div>

                    <!-- Upload New Photo -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium mb-1">Update Photo</label>
                        <input
                            type="file"
                            id="edit_photo"
                            name="photo"
                            accept="image/jpeg,image/png,image/jpg"
                            class="w-full rounded-lg border border-neutral-600 bg-neutral-800 px-4 py-2 text-sm text-neutral-100
                                file:mr-4 file:rounded-md file:border-0 file:bg-blue-600
                                file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-white
                                hover:file:bg-blue-700"
                        >
                        <p class="mt-1 text-xs text-neutral-400">
                            Leave empty to keep current photo. JPG, PNG or JPEG. Max 2MB.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="sm:col-span-2 flex flex-col-reverse sm:flex-row justify-end gap-3 mt-4">
                        <button type="button" onclick="closeEditCustomerModal()"
                            class="px-4 py-2 rounded-lg border border-gray-600 text-gray-200 hover:bg-gray-800">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                            Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <script>
        function editCustomer(id, full_name, location_id, transport_id, phone, description, photo) {
            const modal = document.getElementById('editCustomerModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        
            const form = document.getElementById('editCustomerForm');
            form.action = `/customers/${id}`;
        
            document.getElementById('edit_full_name').value = full_name;
            document.getElementById('edit_location').value = location_id;
            document.getElementById('edit_transport_id').value = transport_id;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_description').value = description || '';

            const photoPreview = document.getElementById('currentPhotoPreview');
            if (photo) {
                photoPreview.innerHTML = `
                    <div class="flex items-center gap-3 rounded-lg border border-neutral-200 p-3 dark:border-neutral-700">
                        <img src="/storage/${photo}" alt="${name}" class="h-16 w-16 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Current Photo</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Upload new photo to replace</p>
                        </div>
                    </div>
                `;
            } else {
                photoPreview.innerHTML = `
                    <div class="rounded-lg border border-dashed border-neutral-300 p-4 text-center dark:border-neutral-600">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">No photo uploaded</p>
                    </div>
                `;
            }
        }
        
        function closeEditCustomerModal() {
            const modal = document.getElementById('editCustomerModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('editCustomerForm').reset();
        }
    </script>
</x-layouts.app>
