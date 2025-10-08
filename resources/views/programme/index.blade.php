@extends('layouts.app')
@section('title','Programme')
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">{{ __('Manage') . ' ' . __('Programme') }}</h3>
    </div>

    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div id="message"></div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form id="createForm">
                        <div class="form-group mb-4">
                            <label for="">Department</label>
                            <select name="department_id" id="department_id"  class="form-control">
                                <option value="">--Select--</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="">Programme</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <button type="submit" class="btn btn-primary" >Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Table list -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{__('List') .' '.__('Programme') }}</h4>
                </div>

                <table
                    id="table_list"
                    class="table"
                    data-toggle="table"
                    data-url="{{ route('programme.show',1) }}"
                    data-side-pagination="server"
                    data-pagination="true"
                    data-page-list="[5,10,20,50,100]"
                    data-search="true"
                    data-show-columns="true"
                    data-show-refresh="true"
                    data-mobile-responsive="true"
                    data-sort-names="id"
                    data-sort-order="desc"
                    data-export-data-type="all"
                    data-export-options='{"fileName":"{{ __("Programme") }}--{{ date("d-m-Y") }}"}'
                    data-show-export="true"
                >

                    <thead>
                        <tr>
                            <th data-field="no" data-formatter="indexFormatter">{{ __('Serial No') }}</th>
                            <th data-field="programme_name" data-sortable="true" >{{ __('Programme name') }}</th>
                            <th data-field="department_name" data-sortable="true"> {{ __('Department name')}} </th>
                            <th data-field="id" data-formatter="actionFormatter" data-align="center" >{{ __('Action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 <div class="col-sm-12">
                    <div class="card">
                        <div id="edit_message"></div>
                        <div class="card-body">
                            <form id="editForm">
                                <div class="form-group mb-4">
                                    <input type="text" id="edit_id">
                                    <label for="">Department</label>
                                    <select name="department_id" id="edit_department_id"  class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="">Programme</label>
                                    <input type="text" class="form-control" name="name" id="edit_name">
                                </div>
                                <button type="submit" class="btn btn-primary" >Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Update changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>

    function indexFormatter(value,row, index){
        return index + 1;
    }

    function actionFormatter(value, row, index){
        console.log(row);
        return`
            <button class="btn btn-sm btn-primary edit-btn" onclick="editProgramme(${row.id})">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" onclick="deleteProgramme(${row.id})">Delete</button>
        `;
    }

    function editProgramme(id){
       // alert(id);
        $('#edit_message').empty();
        $.ajax({
            url:`programme/` + id + `/edit/`,
            method: `GET`,
            data: {
                id: id
            },
            success: function(response){
                console.log(response);

                $('#editModal').modal('show');
                $('#edit_id').val(response.id);
                $('#edit_department_id').val(response.department_id);
                $('#edit_name').val(response.name);
            }
        })
    }

    function deleteProgramme(id){

        if(confirm('are you sure want to delete thr programme' + id + '?' )){
            console.log(id);
             $.ajax({
                url:'/programme/' + id,
                method:`DELETE`,
                data:{
                    _token: '{{ csrf_token() }}',
                },
                success :function(response){
                    $('#message').html(`
                        <div class="alert alert-success">${response.message}</div>
                    `);

                    setTimeout(function(){
                        $('#message').empty();
                    },2000);
                    
                    $('#table_list').bootstrapTable('refresh');
                }

        });

        }
       
    }

    $('#editForm').validate({
        rules: {
            edit_name: {
                required: true
            },
            edit_department_id: {
                required: true
            },
        },
        messages: {
            edit_name:{
                required:"Please enter programme"
            },
            edit_department_id:{
                required:"Please enter Department"
            }

        },
        submitHandler: function(form, event) {
            event.preventDefault();

            id = $("#edit_id").val();
            departmentId = $("#edit_department_id").val();
            name = $('#edit_name').val();

            $.ajax({
                url: `/programme/${id}`,
                method:`PUT`,
                data:{
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id:id,
                    name: name,
                    department_id : departmentId
                },
                success:  function(response){
                    // console.log(response);

                    $('#editForm')[0].reset();
                    $('#editModal').modal('hide');

                    $('#message').html(`<div class="alert alert-success">${response.message}</div>`);

                     setTimeout(function () {
                        $('#message').empty();
                        // alert('Programme updated successfully!');
                    }, 2000);

                    // refresh table if needed
                    $('#table_list').bootstrapTable('refresh');

                },
                error: function(xhr){
                    if(xhr.status == 422){
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        let message =  xhr.responseJSON.message
                        $('#edit_message').html(`
                            <div class="alert alert-danger">${firstError}</div>
                        `);
                    }else{
                        $('#edit_message').html(`
                            <div class="alert alert-danger">${message || 'Something went wrong'}</div>
                        `);
                    }
                }
            });
        }
    });

    $("#createForm").validate({
        rules: {
            name: {
                required: true
            },
            department_id: {
                required: true
            },
            
        },
        messages: {
            name: {
                required: "Please enter programme name"
            },
            department_id: {
                required: "Please enter Department"
            }
            
        },
        submitHandler: function(form) {

            departmentId = $('#department_id').val();
            name = $('#name').val();

            $.ajax({
                url: `{{ route('programme.store') }}`,
                method: `POST`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // or $('#createForm input[name="_token"]').val()
                    department_id: departmentId,
                    name: name
                    // data: formData
                },
                // data: formData,
                success: function(response){
                    // console.log(response);

                    $('#createForm')[0].reset();
                    $('#message').html(
                        `<div class="alert alert-success">Programme saved successfully</div>`
                    );
                     $('#table_list').bootstrapTable('refresh');

                    setTimeout(function() {
                        $('#message').empty();
                    }, 2000);

                },
                error: function(xhr){
                    if(xhr.status == 422){
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        $('#message').html(`
                            <div class="alert alert-danger">${firstError}</div>
                        `);
                    }else{
                        $('#message').html(`
                            <div class="alert alert-danger">${xhr.responseJSON.message || "something went wrong" }<div>
                        `);
                    }

                    setTimeout(function() {
                        $('#message').empty();
                    }, 2000);

                }
            });
        }
 });

</script>
@endpush