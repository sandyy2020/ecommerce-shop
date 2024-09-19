@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid my-2">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Edit Category</h1>
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
		@method('put')
			<!-- @csrf -->
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
								<label for="name">Name</label>
								<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$category->name}}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label for="slug">Slug</label>
								<input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" value="{{$category->slug}}">
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
                                @if($category->image != "")
                                <img class="w-50 my-2" src="{{asset('uploads/images/'.$category->image)}}" alt="">
                                @endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label for="status">Status</label>
								<select name="status" id="status" class="form-control">
									<option {{($category->status==1)?'selected':''}} value="1">Active</option>
									<option {{($category->status==0)?'selected':''}} value="0">Block</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="pb-5 pt-3">
				<button type="submit" id="formSubmit" class="btn btn-primary">Update</button>
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
	$('#formSubmit').click(function(event) {
		event.preventDefault();
		// var data = $('#categoryForm').serialize();
		var data = new FormData($('#categoryForm')[0]); 
		console.log($('#image').val());
		
		$.ajax({
			url: '{{route("categories.update",$category->id)}}',
			type: 'POST',
			data: data,
			contentType: false,
			processData: false,
			// headers: {
            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // },
			success: function(response) {
				if (response.status) {
					alert('Category Updated successfully!');
					window.location.href = "{{ route('categories.index') }}"; // Redirect to categories.index
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