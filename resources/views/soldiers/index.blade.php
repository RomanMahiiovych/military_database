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

                        {{--             Add Button           --}}
                        <div align="right">
                            <a href="{{ route('soldiers.create') }}" class="btn btn-success btn-sm">Add Soldier</a>
                        </div>

                        <br />

                        <table id="data-table" class="table table-bordered data-table">

                            <thead>

                            <tr>

                                <th>Id</th>

                                <th>Firstname</th>

                                <th>Lastname</th>

                                <th>Rank</th>

                                <th>Email</th>

                                <th>Phone</th>

{{--                                <th>Date of Entry</th>--}}

{{--                                <th>Salary</th>--}}

                                <th>Image</th>

                                <th width="100px">Actions</th>

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

{{--DELETE MODAL--}}
<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to remove this soldier?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    var soldier_id;

    $(document).on('click', '.delete', function() {
        soldier_id = $(this).attr('id');
        $('#confirmModal').modal('show');
    });

    $('#ok_button').click( function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax(
            {
                url: "soldiers/destroy/" + soldier_id,
                type: 'delete',
                data: {
                    "id": soldier_id
                },
                beforeSend:function() {
                    $('#ok_button').text('Deleting...');
                },
                success:function(response)
                {
                    setTimeout(function() {
                        $('#confirmModal').modal('hide');
                        $('#data-table').DataTable().ajax.reload();
                        $('#ok_button').text('Delete');
                    }, 1000);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
    });

    $(function () {
        $('.data-table').DataTable({

            processing: true,

            serverSide: true,

            ajax: "{{ route('soldiers.index') }}",

            columns: [

                {data: 'id', name: 'id'},

                {data: 'first_name', name: 'first_name'},

                {data: 'last_name', name: 'last_name'},

                {data: 'rank_id', name: 'rank_id'},

                {data: 'email', name: 'email'},

                {data: 'phone_number', name: 'phone_number'},

                // {data: 'date_of_entry', name: 'date_of_entry'},

                // {data: 'salary', name: 'salary'},

                {data: 'image', name: 'image', orderable: false, searchable: false},

                {data: 'action', name: 'action', orderable: false, searchable: false},

            ],
        });

    });

</script>

