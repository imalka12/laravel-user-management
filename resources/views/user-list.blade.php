@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('User List') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table" id="userList">
                            <colgroup>
                                <col style="width: 5%;">
                                <col style="width: 15%;">
                                <col style="width: 15%;">
                                <col style="width: 20%;">
                                <col style="width: 15%;">
                                <col style="width: 20%;">
                            </colgroup>
                            <thead>
                                <th>#</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>
                                    Actions
                                </th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="userDet">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">User Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name" required
                                        autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="username"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>

                                <div class="col-md-6">
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        required autocomplete="username" autofocus>

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email" required
                                        autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="user_type"
                                    class="col-md-4 col-form-label text-md-end">{{ __('User Type') }}</label>

                                <div class="col-md-6">
                                    <select id="user_type" class="form-control @error('user_type') is-invalid @enderror"
                                        name="user_type" required>
                                        <option value="" selected disabled>Select User Type</option>
                                        @foreach (\App\Models\User::$userTypes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="updateUserBtn" class="btn btn-warning">Update
                                User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(function() {
            loadUsersTable();
        });

        //load user list table
        function loadUsersTable() {
            let url = '/users';
            var row = '';
            let n = 1;

            $.get(url, function(response) {
                console.log(response);
                if (response.length > 0) {
                    $.each(response, function(i, x) {
                        row +=
                            `<tr>
                        <td>${n++}</td>
                        <td>${x.username}</td>
                        <td>${x.name}</td>
                        <td>${x.email}</td>
                        <td>${x.user_type}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary d-inline btn-preview" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user_id="${x.id}">Preview</button>
                            <button type="button" class="btn btn-sm btn-danger d-inline" data-trigger="btn-deactivate" data-user_id="${x.id}">Deactivate</button>
                        </td>
                    </tr>`;
                    });

                    $('#userList tbody').html(row);

                    $('#userList').DataTable();
                } else {
                    row = "<tr><td colspan=\"6\">No Data</td></tr>"
                    $('#userList tbody').html(row);
                }
            }, 'json');
        }

        // Update user details
        function updateUserDetails(user) {
            let url = '/user-update/' + user;
            var formData = $('#userDet').serialize();

            $.post(url, formData, function(response) {
                if (response.success) {
                    swal.fire(
                        "Success",
                        response.success,
                        "success"
                    );
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else if (response.error) {
                    swal.fire(
                        "Failed",
                        response.error,
                        "warning"
                    );
                }
            });
        }


        $(document).on("click", "#updateUserBtn", function() {
            var user = $(this).data('userid');
            updateUserDetails(user);
        });


        //delete user 
        $(document).on("click", '[data-trigger="btn-deactivate"]', function() {
            var user = $(this).data('user_id');


            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var postData = {
                _token: csrfToken
            };

            Swal.fire({
                title: "Are you sure you want to delete this user?",
                showCancelButton: true,
                confirmButtonText: "OK",
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '/user-delete/' + user;
                    $.post(url, postData, function(response) {
                        if (response.success) {
                            swal.fire(
                                "Success",
                                response.success,
                                "success"
                            );
                            loadUsersTable();
                        } else if (response.error) {
                            swal.fire(
                                "Failed",
                                response.error,
                                "warning"
                            );
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire("Error!", "User deletion failed.", "error");
                }
            });
        });
    </script>
@endpush
