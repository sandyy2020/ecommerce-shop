@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Shipping Management</h1>
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
        <form id="shippingForm" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select a country</option>
                                    @if($countries->isNotEmpty())
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                    <option value="rest_of_world">Rest Of The World</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <button type="submit" id="formSubmit" class="btn btn-primary">Create</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            @if($shippingCharges->isNotEmpty())
                            @foreach($shippingCharges as $shippingcharge)
                            <tr>
                                <td>{{$shippingcharge->id}}</td>
                                <td>
                                    {{($shippingcharge->country_id=='rest_of_world') ? 'Rest Of The World' : $shippingcharge->name}}
                                </td>
                                <td>${{$shippingcharge->amount}}</td>
                                <td>
                                    <a href="{{route('shipping.edit',$shippingcharge->id)}}" class="btn btn-primary">Edit</a>
                                    <a href="javascript:void(0);" onclick="deleteRecord({{$shippingcharge->id}});" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </table>


                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection


@section('customJs')

<script>
    $(document).ready(function() {
        // submit the form
        $('#formSubmit').click(function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get the form data
            var formData = new FormData($('#shippingForm')[0]);

            $.ajax({
                url: '{{ route("shipping.store") }}', // Category store route
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token
                },
                success: function(response) {
                    if (response.status) {
                        alert('Shipping added successfully!');
                        window.location.href = "{{ route('shipping.create') }}"; // Redirect on success
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

    function deleteRecord(id) {
        var url = '{{route("shipping.delete","ID")}}';
        var newUrl = url.replace("ID", id);

        if (confirm("Are You Sure You want To Delete??")) {
            $.ajax({
                url: newUrl, // URL to delete the category
                type: 'DELETE',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        // Show the message returned from the server
                        alert(response.message);
                        // Redirect on success
                        window.location.href = "{{ route('shipping.create') }}";
                    } else {
                        // Show the message if the status is false
                        alert(response.message);
                    }
                },
                error: function(jqXHR, exception) {
                    alert('Something went wrong. Please try again.');
                }
            });
        }
    }
</script>

@endsection