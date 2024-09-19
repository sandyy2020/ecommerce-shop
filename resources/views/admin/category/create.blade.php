@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid my-2">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Create Category</h1>
			</div>
			<div class="col-sm-6 text-right">
				<a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="container-fluid">
		<form id="categoryForm" enctype="multipart/form-data">
			@csrf
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
								<label for="image">Image</label>
								<!-- <input type="file" name="image" id="image" class="form-control" placeholder="image"> -->
								<div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br>Drop files here or click to upload.<br><br>                                            
                                    </div>
                                </div>
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
                        <div class="col-md-6">
							<div class="mb-3">
								<label for="status">Show On Home</label>
								<select name="showHome" id="showHome" class="form-control">
									<option value="Yes">Active</option>
									<option value="No">Block</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="pb-5 pt-3">
				<button type="submit" id="formSubmit" class="btn btn-primary">Create</button>
				<a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
			</div>
		</form>
	</div>
	<!-- /.card -->
</section>
<!-- /.content -->

@endsection


@section('customJs')
<script>
	Dropzone.autoDiscover = false;
	$(document).ready(function() {
            new Dropzone("#image", {
                url: "{{ route('temp-images.create') }}",
                paramName: "image",
                maxFiles: 1,
                addRemoveLinks: true,
                acceptedFiles: "image/jpeg,image/png,image/gif",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(file, response) {
                    console.log("Image uploaded successfully:", response);
                    $("<input>").attr({
                        type: "hidden",
                        id: "image_id",
                        name: "image_id",
                        value: response.image_id
                    }).appendTo("#categoryForm");
                },
                error: function(file, response) {
                    console.error("Image upload failed:", response);
                    alert('Image upload failed. Please try again.');
                }
            });
        });
</script>
<script>
	$(document).ready(function() {
	// submit the form
	$('#formSubmit').click(function(event) {
    event.preventDefault(); // Prevent default form submission

    // Get the form data
    var formData = new FormData($('#categoryForm')[0]);

    $.ajax({
        url: '{{ route("categories.store") }}', // Category store route
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token
        },
        success: function(response) {
            if (response.status) {
                alert('Category created successfully!');
                window.location.href = "{{ route('categories.index') }}"; // Redirect on success
            } else {
                var errorMessage = '';
                $.each(response.errors, function(key, value) {
                    errorMessage += value + '\n'; // Collect validation errors
                });
                alert(errorMessage);
            }
        },
        error: function(jqXHR, exception) {
            console.error('An error occurred:', exception);
        }
    });
});
});
</script>
<!-- <script>
    // Initialize Dropzone (Dropzone auto binds if you have class="dropzone")
    Dropzone.options.image = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        acceptedFiles: ".jpeg,.jpg,.png,.pdf,.docx", // accepted file types
        dictDefaultMessage: "Drag files here or click to upload.",
        init: function() {
            this.on("success", function(file, response) {
                console.log("File uploaded successfully");
            });
            this.on("error", function(file, response) {
                console.log("File upload error");
            });
        }
    };
</script> -->



@endsection