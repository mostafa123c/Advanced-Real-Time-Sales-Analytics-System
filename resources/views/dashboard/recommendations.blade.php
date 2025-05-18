@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h2>AI Recommendations</h2>
            <p>Smart recommendations for your business</p>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-lightbulb"></i> AI Sales Analysis & Recommendations</h5>
            </div>
            <div class="card-body">
                <div id="ai-recommendations">
                    <p class="text-muted text-center">Analyzing sales data...</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-cloud-sun"></i> Weather-based Recommendations</h5>
            </div>
            <div class="card-body">
                <div id="weather-recommendations">
                    <p class="text-muted text-center">Analyzing weather data...</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .recommendation-content {
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Weather info box */
        .weather-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        /* List styling */
        .recommendation-list {
            padding-left: 20px;
        }

        .recommendation-list li {
            margin-bottom: 10px;
        }

        /* Success color */
        .text-success {
            color: #28a745;
        }
    </style>
@endsection
