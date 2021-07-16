<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Military databases') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="container">

                        <table class="table table-bordered data-table">

                            <thead>

                            <tr>

                                <th>Id</th>

                                <th>Firstname</th>

                                <th>Lastname</th>

                                <th>Email</th>

                                <th>Phone</th>

                                <th>Date of Entry</th>

                                <th>Salary</th>

                                <th>Image</th>

                                <th width="100px">Action</th>

                            </tr>

                            </thead>

                            <tbody>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>

    $(function () {

        var table = $('.data-table').DataTable({

            processing: true,

            serverSide: true,

            ajax: "{{ route('soldiers.index') }}",

            columns: [

                {data: 'id', name: 'id'},

                {data: 'first_name', name: 'first_name'},

                {data: 'last_name', name: 'last_name'},

                {data: 'email', name: 'email'},

                {data: 'phone_number', name: 'phone_number'},

                {data: 'date_of_entry', name: 'date_of_entry'},

                {data: 'salary', name: 'salary'},

                {data: 'image', name: 'image'},

                {data: 'action', name: 'action', orderable: false, searchable: false},

            ]

        });

    });

</script>

