<x-superadmin-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Set Subscription for: <span class="font-bold">{{ $school->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-gray-500 mb-4">Step 2 of 2: Set the initial subscription plan for this school.</p>

                    <form method="POST" action="{{ route('superadmin.schools.storeSubscription', ['school' => $school->id]) }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="plan_name" :value="__('Subscription Plan')" />
                            <select name="plan_name" id="plan_name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="monthly">Monthly</option>
                                <option value="annually">Annually</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Set Plan & Finish') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
