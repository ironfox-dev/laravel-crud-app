@extends('products.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb" style="margin-top: 50px;">
        <div class="pull-left">
            <h2>My first Laravel Project (CRUD)</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<button type="submit" class="pull-right btn btn-danger delete_all" id="deleteAll">Delete All</button>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="checked_all" />
            </th>
            <th>No</th>
            <th>Name</th>
            <th>Details</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr id="tr_{{$product->id}}">
            <td>
                <input type="checkbox" name="check" id="{{ $product->id }}" class="productcheck" data-productid="{{ $product->id }}" />
            </td>
            <td>{{ ++$i }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->detail }}</td>
            <td>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('products.show',$product->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>

                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! $products->links() !!}

<script type="text/javascript">
jQuery(document).ready(function(){

    $("#deleteAll").on('click', function() {

        var allVals = [];  
        $(".productcheck:checked").each(function() {  
            allVals.push($(this).attr('data-productid'));
        });  

        if(allVals.length <=0)  
        {  
            alert("Please select row.");  
        }  else {  
            var check = confirm("Are you sure you want to delete this row?");  
            if(check == true){  
                var join_selected_values = allVals.join(","); 
                $.ajax({
                    url: "/myproductsDeleteAll",
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: 'ids='+join_selected_values,
                    success: function (data) {
                        console.log("success")
                        if (data['success']) {
                            $(".productcheck:checked").each(function() {  
                                $(this).parents("tr").remove();
                            });
                            alert(data['success']);
                        } else if (data['error']) {
                            alert(data['error']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function (data) {
                        console.log("err")
                        console.log(data.responseText);
                    }
                });
                $.each(allVals, function( index, value ) {
                    $('table tr').filter("[data-row-id='" + value + "']").remove();
                });
            }  
        }  
    })

    $('#checked_all').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".productcheck").prop('checked', true);  
         } else {  
            $(".productcheck").prop('checked',false);  
         }  
    });

})
</script>

@endsection