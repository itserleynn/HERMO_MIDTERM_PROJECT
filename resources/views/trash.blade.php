<x-layouts.app :title="'Customer Trash'">
    <div class="px-4 py-6 max-w-7xl mx-auto space-y-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Customer Trash</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Restore or permanently delete customers
                </p>
            </div>
            <a href="{{ route('dashboard') }}"
               class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                Back to Customers
            </a>
        </div>

        {{-- Summary Card --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Customers in Trash</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $customers->count() }}
                    </h3>
                </div>
                <div class="rounded-full bg-red-100 p-3 dark:bg-red-900/40">
                    <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Trash Table --}}
        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-md p-6 overflow-x-auto">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Deleted Customers
            </h2>

            @if($customers->isEmpty())
                <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-600 p-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        No deleted customers found.
                    </p>
                </div>
            @else
                <table class="w-full min-w-[900px] table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900">
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Photo</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Full Name</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Location</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Transportation</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Phone</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Deleted At</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($customers as $cust)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">

                                {{-- Photo --}}
                                <td class="px-4 py-3">
                                    @if($cust->photo)
                                        <img
                                            src="{{ Storage::url($cust->photo) }}"
                                            class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-100 dark:ring-blue-900"
                                        >
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                            {{ strtoupper(substr($cust->full_name, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Name --}}
                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $cust->full_name }}
                                </td>

                                {{-- Location --}}
                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $cust->location }}
                                </td>

                                {{-- Transportation --}}
                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $cust->transport?->name ?? 'N/A' }}
                                </td>

                                {{-- Phone --}}
                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $cust->phone }}
                                </td>

                                {{-- Deleted At --}}
                                <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $cust->deleted_at->format('M d, Y') }}
                                    <div class="text-xs">
                                        {{ $cust->deleted_at->format('h:i A') }}
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center gap-3">

                                        {{-- Restore --}}
                                        <form method="POST" action="{{ route('customers.restore', $cust->id) }}">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Restore this customer?')"
                                                class="text-green-600 hover:text-green-700 font-medium">
                                                Restore
                                            </button>
                                        </form>

                                        <span class="text-gray-400">|</span>

                                        {{-- Delete Forever --}}
                                        <form method="POST" action="{{ route('customers.force-delete', $cust->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Permanently delete this customer? This cannot be undone!')"
                                                class="text-red-600 hover:text-red-700 font-medium">
                                                Delete Forever
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</x-layouts.app>
