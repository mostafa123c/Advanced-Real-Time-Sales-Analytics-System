<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Analytics Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('storage/css/dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
</head>

<body>
    <div class="dashboard-container">

        @include('partials.sidebar')

        <div class="main-content">
            @include('partials.navbar')
            <div class="content-area">
                @yield('content')
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script src="{{ asset('storage/js/dashboard.js') }}"></script>
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 10000
        };
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        });

        const channel = pusher.subscribe('private-sales-analytics');

        channel.bind('order.created', function(data) {
            const orderData = data.order;
            toastr.success('New order with id ' + orderData.id + ' has been created!', 'New Order');

        });


        channel.bind('analytics.updated', function(data) {
            toastr.info('Sales analytics have been updated', 'Analytics Updated');
            const analyticsData = data.analytics;

            if (analyticsData) {
                $('#total-revenue').text(analyticsData.total_revenue || '$0');
                $('#revenue-change').text(analyticsData.revenue_changes.revenue_change_percentage + '%');
                $('#last-minute-revenue').text(analyticsData.revenue_changes.last_minute_revenue || '$0');
                $('#orders-count').text(analyticsData.orders_count || '0');
                $('#last-updated').text('Last updated: ' + new Date().toLocaleTimeString());
            }

            const $topProductsTable = $('#top-products-table');
            if ($topProductsTable.length > 0 && analyticsData.top_products && analyticsData.top_products
                .length >
                0) {
                $topProductsTable.empty();

                analyticsData.top_products.forEach(product => {
                    const sharePercentage = (
                        (product.total_revenue / analyticsData.total_revenue) *
                        100
                    ).toFixed(1);
                    $topProductsTable.append(`
                        <tr>
                            <td>${product.name}</td>
                            <td>${product.total_sold_quantity}</td>
                            <td>$${product.total_revenue}</td>
                            <td>${sharePercentage}%</td>
                        </tr>
                    `);
                });
            }
            console.log('Dashboard updated with new analytcs data');
        });
    </script>
    @stack('scripts')
</body>

</html>
