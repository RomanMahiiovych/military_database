<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="GET" action="{{ route('soldiers.index') }}">
        @csrf

        <!-- First name -->
            <div>
                <x-label for="first_name" :value="__('First name')" />

                <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="$soldier->first_name" required autofocus />
            </div>

            <!-- Last name -->
            <div>
                <x-label for="last_name" :value="__('Last name')"/>

                <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                         :value="$soldier->last_name" required autofocus/>
            </div>

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="$soldier->email" required autofocus />
            </div>

            <!-- Phone number -->
            <div>
                <x-label for="phone_number" :value="__('Phone number')" />

                <x-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="$soldier->phone_number" required autofocus />
            </div>

            <!-- Date of entry -->
            <div>
                <x-label for="date_of_entry" :value="__('Date of entry')" />

                <x-input id="date_of_entry" class="block mt-1 w-full" type="text" name="date_of_entry" :value="$soldier->date_of_entry" required autofocus />
            </div>

            <!-- Salary -->
            <div>
                <x-label for="salary" :value="__('Salary')" />

                <x-input id="salary" class="block mt-1 w-full" type="text" name="salary" :value="$soldier->salary" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3">
                    {{ __('Back to List') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
