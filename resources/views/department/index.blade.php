@extends('layouts.app')

@section('title', __('Department'))

@section('content')
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">{{ __('Manage') . ' ' . __('Department') }}</h3>
    </div>

     <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div id="message"></div>
        </div>
        <div class="col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                     <form id="createForm">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Department name</label>
                            <input type="text" class="form-control" id="department" name="department">
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
                    <h4 class="card-title">{{ __('List') . ' ' . __('Department') }}</h4>

                    <table
                        id="table_list"
                        class="table"
                        data-toggle="table"
                        data-url="{{ route('department.show', 1) }}"
                        data-side-pagination="server"
                        data-pagination="true"
                        data-page-list="[5, 10, 20, 50, 100]"
                        data-search="false"
                        data-show-columns="true"
                        data-show-refresh="true"
                        data-mobile-responsive="true"
                        data-sort-name="id"
                        data-sort-order="desc"
                        data-export-data-type="all"
                        data-export-options='{"fileName": "{{ __("Department") }}-{{ date("d-m-Y") }}"}'
                        data-show-export="true"
                    >
                        <thead>
                            <tr>
                                <th data-field="id" data-formatter="indexFormatter">{{ __('Serial No') }}</th>
                                <th data-field="name" data-sortable="true">{{ __('Department Name') }}</th>
                                <th data-field="id" data-formatter="actionFormatter" data-align="center" >{{ _('Action') }}</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL (Optional) --}}
    <!--  -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<!-- Bootstrap Table JS -->
<script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.2/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script>
    function indexFormatter(value, row, index) {
        return index + 1;
    }
    $('#table_list').on('load-error.bs.table', function (e, status, res) {
        console.error("Table load error:", status, res);
    });
    $('#createForm').validate({
        rules: {
            department:{
                required:true,
            }
        },
        messages:{
            department: {
                required: "Please enter your department"
            }
        },
        submitHandler: function(form,event){
            event.preventDefault();

            let department = $('#department').val();


            $.ajax({
                url: "{{ route('department.store') }}",
                method: "POST",
                data: {
                    department: department,
                    _token : '{{ csrf_token() }}'
                },
                success: function(response){

                    $('#createForm')[0].reset();
                    $('#message').html(
                     `<div class="alert alert-success">Department created successfully!</div>`
                    );



                    $('#table_list').bootstrapTable('refresh');
                },
                error: function(xhr){
                    // alert('Something went wrong');
                    if (xhr.status === 422) {
                        // validation error
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0]; 
                        $('#message').html(`<div class="alert alert-danger">${firstError}</div>`);
                    } else {
                        $('#message').html(`<div class="alert alert-danger">${xhr.responseJSON.message || "Something went wrong"}</div>`);
                    }
                }
            })
        }
    });

    function actionFormatter(value, row, index){
        return`
        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
            Delete
        </button>
        `;
    }
    $(document).on('click', '.delete-btn' , function () {
        let id = $(this).data('id');
        
        if(confirm("Are you sure want to delete this department?")){
            $.ajax({
                url: `/department/${id}`,
                method: `DELETE`,
                data:{
                    _token: '{{ csrf_token()}}'
                },
                success: function () {
                    
                    $('#message').html(`<div class="alert alert-success">Department deleted successfully</div>`);
                    $('#table_list').bootstrapTable('refresh');
                },
                error: function(xhr){
                    $('#message').html(`<div class="alert alert-danger">${xhr.responseJSON.message || 'Delete failed'}</div>`);
                }

            });
        }
    });
</script>
@endpush
