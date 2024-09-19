@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Brand</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('brand.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form id="brandForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug">
                            </div>
                        </div>
                        <div class="col-md-6">
							<div class="mb-3">
								<label for="status">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="1">Active</option>
									<option value="0">Block</option>
								</select>
							</div>
						</div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="button" id="formSubmit"  class="btn btn-primary">Create</button>
                <a href="brands.html" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
@endsection
@section('customJs')
<script>
    $(document).ready(function() {
        $('#formSubmit').click(function(event) {
            event.preventDefault();
            // var data = $('#categoryForm').serialize();
            var data = new FormData($('#brandForm')[0]);
            // console.log($('#image').val());

            $.ajax({
                url: '{{route("brand.store")}}',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                success: function(response) {
                    if (response.status) {
                        alert('Brand created successfully!');
                        window.location.href = "{{ route('brand.index') }}"; // Redirect to categories.index
                        // element[0].reset(); // Reset form on success
                    } else {
                        var errorMessage = '';
                        $.each(response.errors, function(key, value) {
                            errorMessage += value + '\n'; // Collect all errors
                        });
                        alert(errorMessage); // Show validation errors
                    }

                },
                error: function(jqXHR, exception) {
                    console.log('something went wrong');
                }
            });
        });
    });
</script>
@endsection