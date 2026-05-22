@extends('layouts.app')

@section('title', __('Semester'))

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">{{ __('Manage') . ' ' . __('Semester') }}</h3>
    </div>

    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div id="message"></div>
        </div>
        <div class="col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form id="createForm">
                        <div class="form-group mb-3">
                            <label for="exampleInputEmail1">Semester name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Semester name ">
                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                        </div>
                        <div class="form-group mb-3">
                             <label for="exampleInputEmail1">Programme name</label>
                            <select name="programme_id" id="programme_id" class="form-control">
                                <option value="">-- Select --</option>
                                @forelse($programmes as $id => $name)
                                
                                <option value="{{ $id }}">{{ $name }}</option>
                                @empty

                                <option value="">No programmes available</option>

                                @endforelse
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="exampleInputEmail1">Semester number</label>
                            <input type="number" class="form-control" id="semester_number" name="semester_number" placeholder="Semester number ">
                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                        </div>

                        <div class="form-group mb-3">
                            <label for="exampleInputEmail1">Semester number</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" >
                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                        </div>

                        <div class="form-group ">
                            <label for="exampleInputEmail1">Semester number</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                        </div>
            
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
           
        </div>
    </div>
    

    {{-- TABLE LIST --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('List') . ' ' . __('Semester') }}</h4>

                    <table
                        id="table_list"
                        class="table"
                        data-toggle="table"
                        data-url="{{ route('semesters.show', 1) }}"
                        data-side-pagination="server"
                        data-pagination="true"
                        data-page-list="[5, 10, 20, 50, 100]"
                        data-search="true"
                        data-show-columns="true"
                        data-show-refresh="true"
                        data-mobile-responsive="true"
                        data-sort-name="id"
                        data-sort-order="desc"
                        data-export-data-type="all"
                        data-export-options='{"fileName": "{{ __("Semester") }}-{{ date("d-m-Y") }}"}'
                        data-show-export="true"
                    >
                        <thead>
                            <tr>
                                <th data-field="no" data-formatter="indexFormatter">{{ __('Serial No') }}</th>
                                <th data-field="name" data-sortable="true">{{ __('Name') }}</th>
                                <th data-field="programme_name" data-sortable="true">{{ __('Programme') }}</th>
                                <th data-field="semester_number" data-sortable="true">{{ __('Number') }}</th>
                                <th data-field="start_date" data-sortable="true">{{ __('Start') }}</th>
                                <th data-field="end_date" data-sortable="true">{{ __('End') }}</th>
                                <th data-field="status" data-sortable="true">{{ __('Status') }}</th>
                                <th data-field="id" data-formatter="actionFormatter" data-align="center" >{{ _('Action') }}</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


            </div>
            <div class="modal-body">
                 <div class="card mb-4">
                    <div class="card-body">
                        <div id="update_message"></div>
                         <form id="editForm">
                            @csrf
                            <input type="text" name="id" id="edit_id">
                            <div class="form-group mb-3">
                                <label for="exampleInputEmail1">Semester name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" placeholder="Semester name ">
                                <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                            </div>
                            <div class="form-group mb-3">
                                <label for="exampleInputEmail1">Programme name</label>
                                <select name="programme_id" id="edit_programme_id" class="form-control">
                                    <option value="">-- Select --</option>
                                    @forelse($programmes as $id => $name)
                                    
                                    <option value="{{ $id }}">{{ $name }}</option>
                                    @empty

                                    <option value="">No programmes available</option>

                                    @endforelse
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="exampleInputEmail1">Semester number</label>
                                <input type="number" class="form-control" id="edit_semester_number" name="semester_number" placeholder="Semester number ">
                                <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                            </div>

                            <div class="form-group mb-3">
                                <label for="exampleInputEmail1">Start</label>
                                <input type="date" class="form-control" id="edit_start_date" name="start_date" >
                                <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                            </div>

                            <div class="form-group ">
                                <label for="exampleInputEmail1">End</label>
                                <input type="date" class="form-control" id="edit_end_date" name="end_date">
                                <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                            </div>

                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" id="edit_status" class="form-control">
                                    @forelse($status as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                    @empty
                                    <option value="">Not found</option>
                                    @endforelse
                                </select>
                            </div>
                
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')

<script>

    function indexFormatter(value , row, index){
        return index + 1;
    }

    $('#table_list').on('load-error.bs.table', function (e, status, res){
        console.error("Table load error",status,res);
    })

    $('#createForm').validate({
        rules:{
            name:{
                required:true,
            },
            programme_id:{
                required:true,
            },
            semester_number:{
                required:true,
            },
            start_date:{
                required:true,
            },
            end_date:{
                required:true,
            },
        },
        messages:{
            name:{
                required:'Please enter your Semester name'
            },
            programme_id:{
                required:'Please enter your Programme'
            },
            semester_number:{
                required:'Please enter your semester number'
            },
            start_date:{
                required:'Please enter your start date'
            },
            end_date:{
                required:'Please enter your end date'
            }

        },
        submitHandler: function(form,event){
            // event.preventDefault();

            // let semesterName = $('#semester_name').val();

            let formData = {
                
            }

            // console.log(formData);
            $.ajax({
                url: "{{ route('semesters.store') }}",
                method:"POST",
                data:{
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val(),
                    programme_id: $('#programme_id').val(),
                    semester_number: $('#semester_number').val(),
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                },
                success:function (response){
                    // console.log('success',response);

                    $('#createForm')[0].reset();
                    $('#message').html(
                        `<div class="alert  alert-success">Semester created successfully </div>`
                    );

                    $('#table_list').bootstrapTable('refresh');
                },
                error: function(xhr){
                    // console.log('validation error', xhr.responseJSON.errors);

                    if(xhr.status === 422){
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        $('#message').html(`<div class="alert alert-danger">${firstError}</div>`);
                    } else {
                        $('#message').html(`<div class="alert alert-danger">${xhr.responseJSON.message || "something went wrong" }</div>`);
                    }

                }
                   
            });
        }
    });

    function actionFormatter(value, row, index ){
        // console.log(row);
        return `
           <button class="btn btn-primary btn-sm edit-btn" data-id= "${row.id}" >Edit</button>&nbsp;
        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>  
        `;
    }

    $(document).on('click', '.edit-btn', function (){
        let id = $(this).data('id');
        // console.log(id);
        $('#update_message').empty();
        $.ajax({
            url: `/semesters/${id}/edit`,
            method:`GET`,
            data:{
                id:id,
                _token: '{{ csrf_token() }}'
            },
            success: function (response){
                // console.log(response);
                $('#editModal').modal('show');
                $('#edit_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_programme_id').val(response.programme_id);
                $('#edit_semester_number').val(response.semester_number);
                $('#edit_start_date').val(response.start_date);
                $('#edit_end_date').val(response.end_date);
                $('#edit_status').val(response.status);
            }
        });
    });

    $(document).on('click', '.delete-btn', function(){
        let id = $(this).data('id');
        // console.log(id);

        if(confirm("Are you sure want to delete this Semester?")){
            $.ajax({
                url: `/semesters/${id}`,
                method:`DELETE`,
                data:{
                    _token:'{{ csrf_token() }}'
                },
                success: function (){
                    $('#message').html(`<div class="alert alert-success" >Semester deleted successfully</div>`);
                    $('#table_list').bootstrapTable('refresh');
                },
                error: function(xhr){
                    $('#message').html(`<div class="alert alert-danger" >${xhr.responseJSON.message || 'Delete Failed'}</div>`);
                }
            });
        }
    })

    $("#editForm").validate({
        rules:{
            name:{
                required:true,
            },
            programme_id:{
                required:true,
            },
            semester_number:{
                required:true,
            },
            start_date:{
                required:true,
            },
            end_date:{
                required:true,
            },
            status:{
                required:true,
            },
        },
        messages:{
            name:{
                required:"Please enter Semester name",
            },
            programme_id:{
                required:"Please enter programme",
            },
            semester_number:{
                required:"Please enter Semester number",
            },
            start_date:{
                required:"Please enter Start date",
            },
            end_date:{
                required:"Please enter end date",
            },
            status:{
                required:"Please select status",
            },
        },
        submitHandler: function(form, event){
            event.preventDefault();

            let id = $('#edit_id').val();

            console.log(id);

            formData = $(form).serialize();

            console.log(formData);
            // alert(1);

            $.ajax({
                url:`/semesters/${id}`,
                method:'POST',
                data:$(form).serialize() + '&_method=PUT',
                success : function (response) {
                    // console.log(response);
                    $('#editForm')[0].reset();
                    
                    $('#message').html(`
                    <div class="alert alert-success">Semester updated successfully</div>
                    `);
                    $('#editModal').modal('hide');

                    $('#table_list').bootstrapTable('refresh');
                },
                error: function(xhr){
                    let errorMessage = 'Something went wrong';

                    if(xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors && errors.department) {
                            errorMsg = errors.department[0];
                        }
                    }
                    $('#update_message').html(
                        `<div class="alert alert-danger">${errorMsg}</div>`
                    );
                }
            })
        }
    })
</script>



@endpush