@extends('layouts.app')
@section('content')
<div class="container">
    <div id="order-message" class="mt-2"></div>

    <h1>Add Order</h1>
    <form id="add-order-form">
        <select name="product_id" class="form-control" required>
            @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>

        <input type="number" name="quantity" class="form-control mt-2" placeholder="Quantity" required>

        <button type="submit" class="btn btn-primary mt-3">Add Order</button>
    </form>
</div>

@Push('scripts')
<script>
    $(document).ready(function() {
        $("#add-order-form").on("submit", function(e) {
            e.preventDefault();
            submitOrder();
        });
    });

    function submitOrder() {

        const formData = {
            product_id: $("select[name='product_id']").val(),
            quantity: $("input[name='quantity']").val(),
        };

        $.ajax({
            url: "/api/v1/orders",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            headers: {
                Accept: "application/json",
            },
            success: function(response) {
                $("#order-message").html(
                    '<div class="alert alert-success">Order placed successfully!</div>'
                );
                $("#add-order-form")[0].reset();
            },
            error: function(xhr) {
                let msg = "An error occurred.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $("#order-message").html(
                    `<div class="alert alert-danger">${msg}</div>`
                );
            },
        });
    }
</script>
@endpush
@endsection