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
                <a href="{{route('shipping.create')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form id="shippingForm">     
            <!-- @method('PUT') -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select a country</option>
                                    @if($countries->isNotEmpty())
                                    @foreach($countries as $country)
                                    <option {{($shippingCharge->country_id==$country->id)?'selected':""}}  value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                    <option value="rest_of_world">Rest Of The World</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <input value="{{$shippingCharge->amount}}" type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <button type="submit" id="formSubmit" class="btn btn-primary">Update</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
        
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
                url: '{{ route("shipping.update", $shippingCharge->id) }}', 
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token
                },
                success: function(response) {
                    if (response.status) {
                        alert('Shipping updated successfully!');
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
</script>

@endsection