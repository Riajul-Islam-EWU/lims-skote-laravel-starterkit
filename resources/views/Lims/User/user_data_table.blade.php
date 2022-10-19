@push('css_data_table')
    <!-- DataTables -->
    {{-- <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css"> --}}
        <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

<div class="row" id="user_data_table">
    <div class="col-12" id="user_data_table_col">
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

@push('js_data_table')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
@endpush
