@extends('layouts.app')
@section('title', 'User')

@section('content')
<div class="container">
    <!-- Create User Form -->
    <div class="card mb-4">
        <div class="card-header">Create User</div>
        <div class="card-body">
            <form id="userForm" method="POST" action="{{ route('user.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" >
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <!-- <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div> -->
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">User List</div>
        <div class="search-box">
            <form id="searchForm" action="{{ route('user.search') }}" method="POST">
                @csrf 
                <input type="text" id="user_search" name="user_search" class="form-control w-75" placeholder="Search here..." >
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>S no</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    
                </tbody>
               
            </table>
            <div id="paginationControls" class="p-3 text-center"></div>
        </div>
    </div>
</div>
<div id="notify" class="alert d-none position-fixed end-0 top-0 m-4" style="z-index:1050; min-width: 300px;">
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="editUserForm" class="modal-content">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" id="edit_name" name="name" class="form-control" >
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="edit_email" name="email" class="form-control" >
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="submit">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- delete row Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="deleteUserForm" class="modal-content">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h3>Are you want to delete</h3>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="submit">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<!-- jQuery Validation Plugin SECOND -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        let currentPage = 1; 
        fetchUsers({page : currentPage});
        $('#userForm').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                name: {
                    required: 'Please enter a name'
                },
                email: {
                    required: 'Please enter an email',
                    email: 'Please enter a valid email'
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: $(form).attr('action'), // or your custom route
                    method: $(form).attr('method'),
                    data: $(form).serialize(),
                    success: function (response) {
                        // Optionally reset the form
                        form.reset();
                        fetchUsers({page : currentPage});
                        showNotification('success', response.success);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = '';
                            $.each(errors, function (key, value) {
                                messages += value[0] + '\n';
                            });
                            // alert(messages); // Or show in UI
                            showNotification('danger', messages); // red box
                        } else {
                            alert("An error occurred.");
                        }
                    }
                });

            },
            errorClass: "text-danger",
            errorElement: "div",
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });


    $('#searchForm').validate({
        rules:{
            user_search:{
                required:true,
            },

        },
        messages:{
            user_search:{
                required: "please enter name or email",
            },
        },
        submitHandler: function(form){
            // form.reset();
            let currentPage = 1;

            fetchUsers({page: currentPage, search: true, form: '#searchForm'})
        }
    });

    $('#deleteUserForm').validate({
        
        submitHandler: function(form){
            $.ajax({
                url:$(form).attr('action'),
                method: $(form).attr('method'),
                data: $(form).serialize(),
                success: function(response){
                    $('#deleteModal').modal('hide');
                    showNotification('success,response.success');
                    let currentPage = 1;
                    fetchUsers({page : currentPage});
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    let message = '';
                    for (let field in errors) {
                        message += errors[field][0] + '\n';
                    }
                }
            });
        }
    });


    $('#editUserForm').validate({
        rules: {
            name:{
                required:true,
            },
            edit_email:{
                required:true,
            },
        },
        messages:{
            email:{
                required: "Please enter name",
            },
            email:{
                required: "Please enter name",
            }
        },
        submitHandler: function(form){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: $(form).serialize(),
                success: (response) =>{
                    $('#editModal').modal('hide');
                    form.reset();
                    let currentPage = 1;
                    showNotification('success', response.success);

                    fetchUsers({page : currentPage});
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let message = '';

                        for (let field in errors) {
                            message += errors[field][0] + '\n';
                        }

                        alert(message); // or show in your custom UI
                    }
                },
                
            });
        },
        errorClass: "text-danger",
        errorElement: "div",
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });

    function fetchUsers({page = 1, search = false, form = null }){
        const url =  search ? `/user-search?page=${page}`:`/get-user-list?page=${page}`;

        const method = search ? 'POST' : 'GET';
        const data = search && form ? $(form).serialize() : {};

        $.ajax({
            url: url,
            method: method,
            data: data,
            headers: search ? {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            } : {},
            success: function(response){
                $('#userTableBody').empty();

                $.each(response.data, function(key, value) {
                    $('#userTableBody').append(`
                    <tr>
                        <td>${key + 1}</td>
                        <td>${value.name}</td>
                        <td>${value.email}</td>
                        <td>
                            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal"
                                onclick="fillEditForm(${value.id}, '${value.name}', '${value.email}')">Edit</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                onclick="fillDeleteForm('${value.id}')">Delete</button>
                        </td>
                    </tr>
                    `);
                });

                renderPagination(response.current_page, response.total_pages, search);
            }
            
        });
    }

    function renderPagination(currentPage, totalPages, isSearch = false){
        $('#paginationControls').empty();

        const onClickFn = isSearch ? 'searchGoToPage' : 'goToPage';

        if(currentPage > 1){
            $('#paginationControls').append(`
                <button class="btn btn-sm btn-outline-secondary me-1" onclick="${onClickFn}(${currentPage - 1})">Previous</button>
            `);
        }

        for (let i = 1; i <= totalPages; i++) {
            const activeClass = (i === currentPage) ? 'btn-primary' : 'btn-outline-secondary';
            $('#paginationControls').append(`
                <button class="btn btn-sm ${activeClass} me-1" onclick="${onClickFn}(${i})">${i}</button>
            `);
        }

        if (currentPage < totalPages) {
            $('#paginationControls').append(`
                <button class="btn btn-sm btn-outline-secondary" onclick="${onClickFn}(${currentPage + 1})">Next</button>
            `);
        }
    }

    function goToPage(page) {
        currentPage = page;
        fetchUsers({ page: currentPage });
    }

    function searchGoToPage(page) {
        currentPage = page;
        fetchUsers({ page: currentPage, search: true, form: '#searchForm' });
    }


    function showNotification(type, message) {
        const notify = $('#notify');
        notify.removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass(`alert alert-${type}`)
            .html(message);

        // Auto-dismiss after 3 seconds
        setTimeout(() => {
            notify.addClass('d-none').removeClass(`alert alert-${type}`);
        }, 3000);
    }


    function fillEditForm(id, name, email) {
        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#editUserForm').attr('action', '/users/' + id);
    }

    function fillDeleteForm(id) {
        $('#deleteUserForm').attr('action', '/users/' + id);
    }
</script>
@endpush
@endsection
