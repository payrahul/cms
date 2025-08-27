@extends('layouts.app')
@section('title','Department')

@section('content')
<!-- Bootstrap Table CSS -->
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div id="message"></div>
        </div>
        <div class="col-sm-12">
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
    <div class="row">
        <div class="col-sm-12">
            <h3 class="mt-4">Department List</h3>
            <table class="table table-bordered" id="departmentTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Department Name</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $key => $department)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<!-- Bootstrap Table JS -->
<script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.2/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script>
$(document).ready(function(){
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

                     // Append new row to the table
                    const newRow = `
                        <tr>
                            <td>#</td>
                            <td>${response.department.name}</td>
                            <td>${response.department.created_at}</td>
                        </tr>
                    `;
                    $('#departmentTable tbody').prepend(newRow);

                    // Recalculate row numbers
                    $('#departmentTable tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
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

    // alert(1);
});
</script>


@endpush
@endsection