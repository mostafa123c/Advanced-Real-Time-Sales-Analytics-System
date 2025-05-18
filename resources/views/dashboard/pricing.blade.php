@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h2>Dynamic Pricing</h2>
            <p>AI-powered pricing suggestions for optimal revenue</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-cloud"></i> Weather Impact on Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div id="weather-pricing" class="pricing-container">
                            <p class="text-muted text-center">Loading weather impact data...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-tags"></i> Pricing Suggestions</h5>
                    </div>
                    <div class="card-body">
                        <div id="pricing-suggestions" class="pricing-container">
                            <p class="text-muted text-center">Loading pricing suggestions...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-success {
            color: #28a745 !important;
            font-weight: bold;
        }
    </style>
@endsection
