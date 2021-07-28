<x-guest-layout>
    <x-auth-card>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="post" action="{{ route('soldiers.store') }}" enctype="multipart/form-data">
        @csrf

            <x-slot name="logo">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </x-slot>

        <!-- Photo -->
            <div>
                <x-label for="photo" :value="__('Photo')" />

                <x-input type="file" name="photo" class="block mt-1 w-full" autofocus />
            </div>
            <br>

        <!-- First name -->
            <div>
                <x-label for="first_name" :value="__('First name')" />

                <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" autofocus />
            </div>

            <!-- Last name -->
            <div>
                <x-label for="last_name" :value="__('Last name')"/>

                <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                         :value="old('last_name')" autofocus/>
            </div>

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autofocus />
            </div>

            <!-- Phone number -->
            <div>
                <x-label for="phone_number" :value="__('Phone number')" />

                <x-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" autofocus />
            </div>

            <!-- Date of entry -->
            <div>
                <x-label for="date_of_entry" :value="__('Date of entry')" />

                <x-input id="date_of_entry" class="block mt-1 w-full" type="text" name="date_of_entry" :value="old('date_of_entry')" autofocus />
            </div>

            <!-- Salary -->
            <div>
                <x-label for="salary" :value="__('Salary')" />

                <x-input id="salary" class="block mt-1 w-full" type="text" name="salary" :value="old('salary')" autofocus />
            </div>

            <!-- Rank -->
            <div>
                <x-label for="rank" :value="__('Rank')" />

                <x-select id="rank" class="block mt-1 w-full" name="rank" autofocus>
                    <option value="0" disabled selected>Choose the rank</option>
                    @foreach($ranks as $rank)
                        <option value="{{$rank->id}}">{{ucfirst($rank->rank)}}</option>
                    @endforeach
                </x-select>
            </div>

            <!-- Head -->
            <div>
                <x-label for="head" :value="__('Head')" />

                <x-select id="head" class="block mt-1 w-full" name="head" autofocus>
                </x-select>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3">
                    {{ __('Create') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

<script>
    $(document).ready(function () {

        $('#rank').on('change', function () {
            let rank_id = $(this).val();
            $('#head').empty();
            $('#head').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: '/heads/' + rank_id,
                dataType: 'json',
                success: function (response) {
                    var response = JSON.parse(JSON.stringify(response));
                    $('#head').empty();
                    $('#head').append(`<option value="0" disabled selected>Choose the Head</option>`);
                    response.forEach(element => {
                        $('#head').append(`<option value="${element['id']}">${element['first_name']} ${element['last_name']}</option>`);
                    });
                }
            });
        });

        // Phone mask
        $(function () {
            let phone = document.getElementById('phone_number');
            let phoneMask = IMask(
                phone, {
                    mask: "+38 (\\000) 000 00 00",
                }
            );
        });

        //DatePicker
        $(function () {
            $("#date_of_entry").datepicker({
                dateFormat: "yy-mm-dd"
            });
        });
    });
</script>
