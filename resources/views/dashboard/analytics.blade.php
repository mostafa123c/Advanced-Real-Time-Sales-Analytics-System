@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h2>Sales Analytics</h2>
            <p>Real-time monitoring of sales performance</p>
        </div>

        <div id="analytics-dashboard">
            <div class="row stats-row">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon revenue-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="total-revenue">$0</h3>
                            <p>Total Revenue <span id="revenue-change" class="ms-2"></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon orders-icon">
                            <i class="bi bi-bag-check"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="orders-count">0</h3>
                            <p>Orders (Last Minute)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon customers-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="last-minute-revenue">$0</h3>
                            <p>Last Minute Revenue</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-trophy"></i> Top Selling Products</h5>
                    <span class="text-muted small" id="last-updated">Last updated: --:--:--</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity Sold</th>
                                    <th>Revenue</th>
                                    <th>Market Share</th>
                                </tr>
                            </thead>
                            <tbody id="top-products-table">
                                <tr>
                                    <td colspan="4" class="text-center">Loading data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
