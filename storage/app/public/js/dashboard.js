$(document).ready(function () {
    $("#sidebar-toggle").on("click", function () {
        $(".sidebar").toggleClass("active");
        $(".main-content").toggleClass("active");
    });

    function loadPageData() {
        const path = window.location.pathname;

        if (path.includes("/analytics") || path === "/dashboard") {
            loadAnalyticsData();
        } else if (path.includes("/recommendations")) {
            loadRecommendationsData();
        } else if (path.includes("/pricing")) {
            loadPricingData();
        }
    }

    function loadAnalyticsData() {
        console.log("Loading analytics data from API...");

        $.ajax({
            url: "/api/v1/analytics",
            method: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    updateAnalyticsDashboard(response.data);
                } else {
                    console.error("API returned error:", response);
                    $("#analytics-dashboard").html(
                        '<p class="text-muted text-center">Failed to load analytics data</p>'
                    );
                }
            },
            error: function (error) {
                console.error("API request failed:", error);
                $("#analytics-dashboard").html(
                    '<p class="text-muted text-center">Failed to load analytics data</p>'
                );
            },
        });
    }

    function loadRecommendationsData() {
        console.log("Loading recommendations data from API...");

        $.ajax({
            url: "/api/v1/recommendations",
            method: "GET",
            dataType: "json",
            success: function (response) {
                updateRecommendationsDashboard(response);
            },
            error: function (error) {
                console.error("API request failed:", error);

                $("#ai-recommendations").html(
                    '<p class="text-muted text-center">Failed to load recommendations data</p>'
                );
                $("#weather-recommendations").html(
                    '<p class="text-muted text-center">Failed to load weather recommendations</p>'
                );
            },
        });
    }

    function loadPricingData() {
        console.log("Loading pricing data from API...");

        $.ajax({
            url: "/api/v1/pricing-suggestions",
            method: "GET",
            dataType: "json",
            success: function (response) {
                updatePricingDashboard(response);
            },
            error: function (error) {
                console.error("API request failed:", error);
                $("#pricing-suggestions").html(
                    '<p class="text-muted text-center">Failed to load pricing data</p>'
                );
            },
        });
    }

    function updateAnalyticsDashboard(data) {
        $("#total-revenue").text(data.total_revenue);
        $("#orders-count").text(data.orders_count);

        if (
            data.revenue_changes &&
            data.revenue_changes.last_minute_revenue !== undefined
        ) {
            $("#last-minute-revenue").text(
                data.revenue_changes.last_minute_revenue
            );
        }

        if (data.revenue_changes) {
            const changePercentage =
                data.revenue_changes.revenue_change_percentage;
            const changeElement = $("#revenue-change");

            if (changeElement.length) {
                const changeText =
                    changePercentage >= 0
                        ? `+${changePercentage}%`
                        : `${changePercentage}%`;

                const changeClass =
                    changePercentage >= 0 ? "text-success" : "text-danger";

                changeElement
                    .text(changeText)
                    .removeClass("text-success text-danger")
                    .addClass(changeClass);
            }
        }

        const topProductsTable = $("#top-products-table");
        topProductsTable.empty();

        if (data.top_products && data.top_products.length > 0) {
            data.top_products.forEach((product) => {
                const sharePercentage = (
                    (product.total_revenue / data.total_revenue) *
                    100
                ).toFixed(1);

                topProductsTable.append(`
                    <tr>
                        <td><strong>${product.name}</strong></td>
                        <td>${product.total_sold_quantity}</td>
                        <td>${product.total_revenue}</td>
                        <td>${sharePercentage}%</td>
                    </tr>
                `);
            });
        }

        $("#last-updated").text(
            "Last updated: " + new Date().toLocaleTimeString()
        );
    }

    function updateRecommendationsDashboard(data) {
        const aiRecommendations = $("#ai-recommendations");
        aiRecommendations.empty();

        if (!data.sales_recommendations) {
            aiRecommendations.html("<p>No recommendations available</p>");
            return;
        }

        try {
            let recommendations;
            if (typeof data.sales_recommendations === "string") {
                try {
                    recommendations = JSON.parse(data.sales_recommendations);
                } catch (e) {
                    aiRecommendations.html(
                        `<div class="recommendation-content">${data.sales_recommendations
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;")
                            .replace(/\n/g, "<br>")}</div>`
                    );
                    return;
                }
            } else {
                recommendations = data.sales_recommendations;
            }

            let html = '<div class="recommendation-content">';

            if (
                recommendations.recommendations &&
                recommendations.recommendations.length > 0
            ) {
                html += "<div class='recommendation-section'>";
                html +=
                    "<h3 class='section-title'><i class='bi bi-lightbulb'></i> Strategic Recommendations</h3>";
                html += "<ul class='recommendation-list'>";
                recommendations.recommendations.forEach((item) => {
                    if (typeof item === "object" && item.title) {
                        html += `<li>
                            <div class="recommendation-item">
                                <h4 class="item-title">${item.title}</h4>
                                <p class="item-details">${
                                    item.details || ""
                                }</p>
                            </div>
                        </li>`;
                    } else {
                        html += `<li>${item}</li>`;
                    }
                });
                html += "</ul></div>";
            }

            if (
                recommendations.products &&
                recommendations.products.length > 0
            ) {
                html += "<div class='recommendation-section'>";
                html +=
                    "<h3 class='section-title'><i class='bi bi-graph-up-arrow'></i> Products to Promote</h3>";
                html += "<div class='card-grid'>";
                recommendations.products.forEach((item) => {
                    if (typeof item === "object" && item.name) {
                        html += `
                        <div class="recommendation-card">
                            <h4 class="product-title">${item.name}</h4>
                            <p class="product-reason">${item.reason || ""}</p>
                            <p class="product-action"><strong>Action:</strong> ${
                                item.action || ""
                            }</p>
                        </div>`;
                    } else {
                        html += `<div class="recommendation-card"><p>${item}</p></div>`;
                    }
                });
                html += "</div></div>";
            }

            if (
                recommendations.pricing_suggestions &&
                recommendations.pricing_suggestions.length > 0
            ) {
                html += "<div class='recommendation-section'>";
                html +=
                    "<h3 class='section-title'><i class='bi bi-tags'></i> Pricing Adjustments</h3>";
                html +=
                    "<table class='table table-striped'>" +
                    "<thead><tr><th>Product</th><th>Suggestion</th><th>Change</th><th>Rationale</th></tr></thead><tbody>";

                recommendations.pricing_suggestions.forEach((item) => {
                    if (typeof item === "object" && item.name) {
                        const percentageClass =
                            item.percentage && item.percentage.includes("+")
                                ? "text-success"
                                : "text-danger";

                        html += `<tr>
                            <td><strong>${item.name}</strong></td>
                            <td>${item.suggestion || ""}</td>
                            <td class="${percentageClass} pricing-change">${
                            item.percentage || ""
                        }</td>
                            <td class="pricing-rationale">${
                                item.rationale || ""
                            }</td>
                        </tr>`;
                    } else {
                        html += `<tr><td colspan="4">${JSON.stringify(
                            item
                        )}</td></tr>`;
                    }
                });
                html += "</tbody></table></div>";
            }

            if (
                recommendations.product_bundling_suggestions &&
                recommendations.product_bundling_suggestions.length > 0
            ) {
                html += "<div class='recommendation-section'>";
                html +=
                    "<h3 class='section-title'><i class='bi bi-box-seam'></i> Product Bundle Opportunities</h3>";
                html += "<div class='bundle-container'>";

                recommendations.product_bundling_suggestions.forEach((item) => {
                    if (typeof item === "object") {
                        const products = Array.isArray(item.products)
                            ? item.products.join(" + ")
                            : "Bundle";

                        html += `<div class="bundle-card">
                            <h4 class="bundle-title">${
                                item.name || "Bundle"
                            }</h4>
                            <p class="bundle-products">${products}</p>
                            <p class="bundle-reason">${item.reason || ""}</p>
                            <p class="bundle-impact"><strong>Impact:</strong> ${
                                item.expected_impact || ""
                            }</p>
                        </div>`;
                    } else {
                        html += `<div class="bundle-card"><p>${item}</p></div>`;
                    }
                });
                html += "</div></div>";
            }

            if (
                recommendations.product_cross_selling_suggestions &&
                recommendations.product_cross_selling_suggestions.length > 0
            ) {
                html += "<div class='recommendation-section'>";
                html +=
                    "<h3 class='section-title'><i class='bi bi-arrow-left-right'></i> Cross-Selling Strategies</h3>";
                html += "<div class='cross-sell-container'>";

                recommendations.product_cross_selling_suggestions.forEach(
                    (item) => {
                        if (typeof item === "object" && item.main_product) {
                            const crossProducts = Array.isArray(
                                item.cross_sell_products
                            )
                                ? item.cross_sell_products.join(", ")
                                : item.cross_sell_products;

                            html += `<div class="cross-sell-card">
                            <div class="cross-sell-flow">
                                <div class="main-product">${
                                    item.main_product
                                }</div>
                                <div class="arrow">→</div>
                                <div class="cross-products">${crossProducts}</div>
                            </div>
                            <p class="cross-sell-reason">${
                                item.reason || ""
                            }</p>
                            <p class="cross-sell-target"><strong>Target:</strong> ${
                                item.target_customer || ""
                            }</p>
                        </div>`;
                        } else {
                            html += `<div class="cross-sell-card"><p>${JSON.stringify(
                                item
                            )}</p></div>`;
                        }
                    }
                );
                html += "</div></div>";
            }

            if (recommendations.analysis_summary) {
                html += "<div class='recommendation-section summary-section'>";
                html +=
                    "<h3 class='section-title'><i class='bi bi-file-earmark-text'></i> Analysis Summary</h3>";
                html += `<div class="analysis-summary">${recommendations.analysis_summary}</div>`;
                html += "</div>";
            }

            html += `
            <style>
                .recommendation-section {
                    margin-bottom: 2rem;
                    padding: 1.5rem;
                    border-radius: 8px;
                    background-color: #f8f9fa;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                }

                .section-title {
                    color: #2c3e50;
                    border-bottom: 2px solid #3498db;
                    padding-bottom: 0.5rem;
                    margin-bottom: 1.5rem;
                    font-size: 1.4rem;
                    font-weight: 600;
                }

                .section-title i {
                    margin-right: 0.5rem;
                    color: #3498db;
                }

                .item-title, .product-title, .bundle-title {
                    color: #2980b9;
                    font-size: 1.1rem;
                    font-weight: 600;
                    margin-bottom: 0.5rem;
                }

                .item-details, .product-reason, .bundle-reason, .cross-sell-reason {
                    color: #555;
                    font-size: 0.95rem;
                    margin-bottom: 0.75rem;
                }

                .recommendation-card, .bundle-card, .cross-sell-card {
                    background-color: white;
                    border-radius: 6px;
                    padding: 1rem;
                    margin-bottom: 1rem;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                    border-left: 3px solid #3498db;
                }

                .bundle-products {
                    font-weight: bold;
                    color: #16a085;
                }

                .cross-sell-flow {
                    display: flex;
                    align-items: center;
                    margin-bottom: 0.75rem;
                }

                .main-product {
                    font-weight: bold;
                    padding: 0.25rem 0.5rem;
                    background-color: #e3f2fd;
                    border-radius: 4px;
                }

                .cross-products {
                    font-weight: bold;
                    padding: 0.25rem 0.5rem;
                    background-color: #e8f5e9;
                    border-radius: 4px;
                }

                .arrow {
                    margin: 0 1rem;
                    color: #7f8c8d;
                    font-size: 1.2rem;
                }

                .pricing-change {
                    font-weight: bold;
                }

                .summary-section {
                    background-color: #edf2f7;
                }

                .analysis-summary {
                    font-size: 1rem;
                    line-height: 1.6;
                    color: #34495e;
                    padding: 0.5rem;
                    background-color: white;
                    border-radius: 6px;
                    border-left: 4px solid #3498db;
                }
            </style>
            `;

            html += "</div>";
            aiRecommendations.html(html);
        } catch (error) {
            console.error("Error displaying recommendations:", error);
            aiRecommendations.html("<p>Error displaying recommendations</p>");
        }

        const weatherRecommendations = $("#weather-recommendations");
        weatherRecommendations.empty();

        if (
            data.weather_recommendations &&
            data.weather_recommendations.recommendations
        ) {
            const weatherInfo = data.weather_recommendations.weather;
            const recommendations =
                data.weather_recommendations.recommendations;

            const weatherInfoHtml = `
                <div class="weather-info mb-3">
                    <h6><i class="bi bi-thermometer-half me-2"></i>Current Weather</h6>
                    <p><strong>Temperature:</strong> ${weatherInfo.temperature}°C</p>
                    <p><strong>Humidity:</strong> ${weatherInfo.humidity}%</p>
                    <p><strong>Conditions:</strong> ${weatherInfo.description}</p>
                </div>
            `;

            const weatherList = $('<ul class="recommendation-list"></ul>');
            recommendations.forEach((rec) => {
                weatherList.append(`<li>${rec}</li>`);
            });

            weatherRecommendations.append(weatherInfoHtml);
            weatherRecommendations.append(weatherList);
        } else if (data.weather && data.weather.length > 0) {
            weatherRecommendations.html(
                '<p class="text-muted text-center">No weather recommendations available</p>'
            );
        }
    }

    function updatePricingDashboard(data) {
        const pricingSuggestions = $("#pricing-suggestions");
        pricingSuggestions.empty();

        if (
            data.pricing_adjustments &&
            data.pricing_adjustments.pricing_edits
        ) {
            const table = $(`
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Category</th>
                            <th>Price Adjustment</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody id="pricing-suggestions-table"></tbody>
                </table>
            `);

            const tableBody = table.find("tbody");
            data.pricing_adjustments.pricing_edits.forEach((item) => {
                const priceChange = item.price_adjustment.includes("+")
                    ? "text-success"
                    : "text-danger";

                tableBody.append(`
                    <tr>
                        <td><strong>${item.product_category}</strong></td>
                        <td class="${priceChange}">${item.price_adjustment}</td>
                        <td>${item.reason}</td>
                    </tr>
                `);
            });

            pricingSuggestions.append(table);
        } else if (data.suggestions && data.suggestions.length > 0) {
            pricingSuggestions.html(
                '<p class="text-muted text-center">No pricing suggestions available</p>'
            );
        }

        const weatherPricing = $("#weather-pricing");
        weatherPricing.empty();

        if (data.pricing_adjustments && data.pricing_adjustments.weather) {
            const weather = data.pricing_adjustments.weather;
            weatherPricing.html(`
                <div class="weather-impact">
                    <h6>Current Weather Conditions:</h6>
                    <p><strong>Temperature:</strong> ${weather.temperature}°C</p>
                    <p><strong>Humidity:</strong> ${weather.humidity}%</p>
                    <p><strong>Conditions:</strong> ${weather.description}</p>
                    <p class="mt-3"><strong>Impact:</strong> Weather conditions are affecting pricing recommendations as shown above.</p>
                </div>
            `);
        } else if (data.weather) {
            weatherPricing.html(
                '<p class="text-muted text-center">No weather impact data available</p>'
            );
        }

        const competitorAnalysis = $("#competitor-analysis");
        competitorAnalysis.empty();
        competitorAnalysis.html(
            '<p class="text-muted text-center">Competitor analysis is currently unavailable in this version</p>'
        );
    }

    loadPageData();
});
