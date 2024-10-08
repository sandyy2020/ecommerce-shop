@extends('admin.layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Edit Product</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
                    <form action=""  id="productForm" enctype="multipart/form-data">
                    @method('put')
					<!-- Default box -->
					<div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-body">								
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{$product->title}}">	
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="slug">Slug</label>
                                                    <input type="text" name="slug" id="slug" class="form-control" placeholder="slug" value="{{$product->slug}}">	
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description">Short Description</label>
                                                    <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="short_description">{{$product->short_description}}</textarea>
                                                </div>
                                            </div>  
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{{$product->description}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description">Shipping & Returns</label>
                                                    <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="shipping_returns">{{$product->shipping_returns}}</textarea>
                                                </div>
                                            </div>    
                                                                                      
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Media</h2>								
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">    
                                                <br>Drop files here or click to upload.<br><br>                                            
                                            </div>
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Pricing</h2>								
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="price">Price</label>
                                                    <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{$product->price}}">	
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="compare_price">Compare at Price</label>
                                                    <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" value="{{$product->compare_price}}">
                                                    <p class="text-muted mt-3">
                                                        To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                                    </p>	
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Inventory</h2>								
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sku">SKU (Stock Keeping Unit)</label>
                                                    <input type="text" name="sku" id="sku" class="form-control" placeholder="sku" value="{{$product->sku}}">	
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="barcode">Barcode</label>
                                                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" value="{{$product->barcode}}">	
                                                </div>
                                            </div>   
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="hidden" name="track_qty" value="No">
                                                        <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" {{($product->track_qty == 'Yes') ? 'checked':''}}>
                                                        <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" value="{{$product->qty}}">	
                                                </div>
                                            </div>                                         
                                        </div>
                                    </div>	                                                                      
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Product status</h2>
                                        <div class="mb-3">
                                            <select name="status" id="status" class="form-control">
                                                <option {{($product->status == 1) ? 'selected' : ''}}  value="1">Active</option>
                                                <option {{($product->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card">
                                    <div class="card-body">	
                                        <h2 class="h4  mb-3">Product category</h2>
                                        <div class="mb-3">
                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control">
                                                <option value="">Select a Category</option>
                                                @if($categories->isNotEmpty())
                                                @foreach($categories as $category)
                                                <option {{($product->category_id == $category->id) ? 'selected':''}} value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                                @endif
                                               
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category">Sub category</label>
                                            <select name="sub_category" id="sub_category" class="form-control">
                                            <option value="">Select a Subcategory</option>
                                            @if($subCategories->isNotEmpty())
                                                @foreach($subCategories as $subCategory)
                                                <option {{($product->sub_category_id == $subCategory->id) ? 'selected':''}} value="{{$subCategory->id}}">{{$subCategory->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Product brand</h2>
                                        <div class="mb-3">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Select a Brand</option>
                                                @if($brands->isNotEmpty())
                                                @foreach($brands as $brand)
                                                <option {{($product->brand_id == $brand->id) ? 'selected':''}} value="{{$brand->id}}">{{$brand->name}}</option>
                                                @endforeach
                                                @endif
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Featured product</h2>
                                        <div class="mb-3">
                                            <select name="is_featured" id="is_featured" class="form-control">
                                                <option {{($product->is_featured == 'No') ? 'selected':''}} value="No">No</option>
                                                <option {{($product->is_featured == 'Yes') ? 'selected':''}} value="Yes">Yes</option>                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>                                 
                            </div>
                        </div>
						
						<div class="pb-5 pt-3">
							<button type="button" id="formSubmit" class="btn btn-primary">Update</button>
							<a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
					</div>
                  
                    </form>
					<!-- /.card -->
				</section>
				<!-- /.content -->

@endsection
@section('customJs')

<script>
$(document).ready(function(){
    $('#category').on('change', function(){
        var categoryId = $(this).val();
        
        if(categoryId) {
            $.ajax({
                url: "{{ route('get.subcategories') }}", // Route for fetching subcategories
                type: "GET",
                data: { category_id: categoryId },
                success: function(data) {
                    if(data.subcategories.length > 0) {
                        $('#sub_category').empty().append('<option value="">Select a Subcategory</option>');
                        $.each(data.subcategories, function(key, value){
                            $('#sub_category').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                    } else {
                        $('#sub_category').empty().append('<option value="">No subcategories available</option>');
                    }
                }
            });
        } else {
            $('#sub_category').empty().append('<option value="">Select a Subcategory</option>');
        }
    });
});
$(document).ready(function() {
	$('#formSubmit').click(function(event) {
		event.preventDefault();
		// var data = $('#categoryForm').serialize();
		var data = new FormData($('#productForm')[0]); 
		// console.log($('#image').val());
		
		$.ajax({
			url: '{{route("products.update",$product->id)}}',
			type: 'POST',
			data: data,
			contentType: false,
			processData: false,
			// headers: {
            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // },
			success: function(response) {
				if (response.status) {
					alert('Product created successfully!');
					window.location.href = "{{ route('products.index') }}"; // Redirect to products.index
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