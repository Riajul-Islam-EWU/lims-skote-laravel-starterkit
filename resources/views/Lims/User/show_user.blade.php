@extends('layouts.master')

@section('title')
    Show User
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            LIMS
        @endslot
        @slot('title')
            Show User
        @endslot
    @endcomponent

    <div class="row" id="user_data_table">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">User List:</h4>
                    <p class="card-title-desc">All Users are listed in the data table here.
                    </p>

                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                        {{-- <a href="{{ url('/add_user') }}" class="btn btn-success my-3"><i
                                class="fa-solid fa-circle-plus"></i> Add new User</a> --}}

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success my-3" data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                            <i class="fa-solid fa-circle-plus"></i> Add new User
                        </button>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($show_user_data as $key => $data)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->email }}</td>
                                    @if ($data->status == 1)
                                        <td>Active</td>
                                    @else
                                        <td>Inactive</td>
                                    @endif
                                    <td>
                                        <a href="{{ url('/edit_user/' . $data->id) }}" class="btn btn-warning"><i
                                                class="fa-solid fa-pen-to-square"></i> Edit</a>
                                        {{-- <a href="{{ url('/delete_user/' . $data->id) }}" class="btn btn-danger"
                                            onclick="return confirm('Delete Data?')"><i class="fa-solid fa-trash-can"></i>
                                            Delete</a> --}}
                                        <button type="submit" class="btn btn-danger delete_user"
                                            data-id="{{ $data->id }}"><i class="fa-solid fa-trash-can"></i>
                                            Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <!-- Add User Modal -->
    @include('Lims/user/create_user_modal')
@endsection

@section('script')
    <!-- apexcharts -->
    {{-- <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script> --}}

    <!-- dashboard init -->
    {{-- <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script> --}}

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#create_user_form').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "{{ route('saveUser') }}",
                    method: "POST",
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $(document).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.saveStatus == 1) {
                            $('#create_user_form')[0].reset();
                            $('#addUserModal').modal('toggle');
                            $("#user_data_table").load(location.href + " #user_data_table");
                            alert(data.Message);
                        } else if (data.saveStatus == 0) {
                            $.each(data.error, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                            alert(data.Message);
                        }
                    }
                })
            });
        });

        $(document).ready(function() {
            $('.delete_user').click(function(event) {
                event.preventDefault();
                if (confirm('Delete Data?')) {
                    var id = $(this).data("id");
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        url: "{{ url('delete_user') }}" + "/" + id,
                        method: "POST",
                        data: {
                            "_token": token,
                            "id": id,
                        },
                        success: function(response) {
                            if (response.deleteStatus == 1) {
                                $("#user_data_table").load(location.href + " #user_data_table");
                                alert(response.Message);
                            } else if (data.deleteStatus == 0) {
                                alert(response.Message);
                            }
                        }
                    })
                }
            });
        });
    </script>
@endsection