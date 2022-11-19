<!-- Date picker Js -->
<script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<!-- Required datatable js -->
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<!-- Datatable init js -->
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>

<!-- Sweet alert js -->
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- toastr js -->
<script src="{{ URL::asset('assets/libs/toastr/toastr.min.js') }}"></script>

<script>
    $(document).ready(function() {
        fetchAllIdeaBoxes();

        // Fetch all Idea Box ajax request
        function fetchAllIdeaBoxes() {
            $.ajax({
                url: '{{ route('fetchAllIdeaBox') }}',
                method: 'get',
                success: function(response) {
                    $("#show_all_idea_boxes").html(response);
                    var table = $('#datatable-buttons').DataTable({
                        // lengthChange: false,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, 'All'],
                        ],
                        buttons: ['copy', 'excel', 'pdf', 'colvis'],
                        order: [
                            [0, 'asc']
                        ],
                    });
                    table.buttons().container().appendTo(
                        '#datatable-buttons_wrapper .col-md-6:eq(0)');
                    $(".dataTables_length select").addClass('form-select form-select-sm');
                }
            });
        }

        toastr.options.preventDuplicates = true;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        // Create Idea Box ajax request
        $("#create_idea_box_form").on("submit", function(e) {
            e.preventDefault();
            var form = this;
            $("#add_idea_box_btn_span").text('Saving...');
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: "JSON",
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $(form).find("span.error-text").text("");
                },
                success: function(data) {
                    if (data.code == 0) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find("span." + prefix + "_error").text(val[0]);
                        });
                        toastr.error(data.Message);
                    } else if (data.code == 1) {
                        $(form)[0].reset();
                        $("#addIdeaBoxModal").modal("hide");
                        Swal.fire(
                            'Added!',
                            'Idea Added Successfully!',
                            'success'
                        )
                        fetchAllIdeaBoxes();
                        toastr.success(data.Message);
                    }
                    $("#add_idea_box_btn_span").text('Create Idea');

                },
            });
        });

        // Edit Idea Box ajax request
        $(document).on('click', '.edit_idea_box', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('editIdeaBox') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // console.log(response)
                    $("#e_idea_box_id").val(id);
                    $("#e_title").val(response.title);
                    $("#e_description").val(response.description);
                    $("#e_district").val($("#e_district option").eq(response.district)
                        .val());
                    if (response.status == 1) {
                        $("#e_status").attr("checked", true);
                    } else if (response.status == 0) {
                        $("#e_status").removeAttr("checked");
                    }
                }
            });
        });

        // Update Idea Box ajax request
        $(document).on('submit', '#edit_idea_box_form', function(e) {
            e.preventDefault();
            var form = this;
            $("#edit_idea_box_btn_span").text('Updating...');
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: "JSON",
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $(form).find("span.error-text").text("");
                },
                success: function(data) {
                    if (data.code == 0) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find("span." + prefix + "_error").text(val[0]);
                        });
                        toastr.error(data.Message);
                    } else if (data.code == 1) {
                        // console.log(data.Message)
                        $(form)[0].reset();
                        $("#editIdeaBoxModal").modal("hide");
                        Swal.fire(
                            'Added!',
                            'Idea Edited Successfully!',
                            'success'
                        )
                        fetchAllIdeaBoxes();
                        toastr.success(data.Message);
                    }
                    $("#edit_idea_box_btn_span").text('Update Idea');
                },
            });
        });

        // Delete Idea Box ajax request
        $(document).on('click', '.delete_idea_box', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            let csrf = '{{ csrf_token() }}';
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#34C38F',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('delete_idea_box') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            if (response.code == 0) {
                                console.log(response);
                                Swal.fire(
                                    'Caution!',
                                    'Something went wrong!',
                                    'error'
                                )
                                fetchAllIdeaBoxes();
                                toastr.error(response.Message);
                            } else if (response.code == 1) {
                                console.log(response);
                                Swal.fire(
                                    'Deleted!',
                                    'Idea has been deleted.',
                                    'success'
                                )
                                fetchAllIdeaBoxes();
                                toastr.success(response.Message);
                            }
                        }
                    });
                }
            })
        });

        // Show Idea Box ajax request
        $(document).on('click', '.show_idea_box', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('showIdeaBox') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response)
                    $(".idea_box_id").text(response.id);
                    $(".idea_box_description").text(response.description);
                    $(".idea_box_district").text(response.district);
                    $(".idea_box_document").attr("href", response.document);
                    $(".idea_box_status").text(response.status);
                }
            });
        });
    });
</script>
