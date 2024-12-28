@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Revenue Analysis</h1>
    </div>
    <div class="row">

        <!-- Daily Revenue -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Daily Revenue</h4>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Weekly Revenue -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Weekly Revenue</h4>
                </div>
                <div class="card-body">
                    <canvas id="weeklyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Monthly Revenue</h4>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="section-header">
        <h1>Product Analysis</h1>
    </div>
    <div class="row">
        <div class="row">
            <!-- Biểu đồ Top Selling Products -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Top 5 Selling Products</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ Most Stocked Products -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Top 5 Most Stocked Products</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="mostStockedProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <!-- Popular Categories -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Popular Categories</h4>
                </div>
                <div class="card-body">
                    <canvas id="popularCategoriesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="section-header">
        <h1>Vendor Performance Analysis</h1>
    </div>
    <div class="row">
        <!-- Revenue Contribution -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Revenue Contribution</h4>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Delivery Performance -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Delivery Performance</h4>
                </div>
                <div class="card-body">
                    <canvas id="deliveryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Vendor Reviews -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Vendor Reviews</h4>
                </div>
                <div class="card-body">
                    <canvas id="reviewsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-financial/dist/chartjs-chart-financial.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Daily Revenue Chart
        const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
        new Chart(dailyRevenueCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($revenueDaily as $data) '{{ $data->date }}', @endforeach
                ],
                datasets: [{
                    label: 'Revenue',
                    data: [
                        @foreach($revenueDaily as $data) {{ $data->revenue }}, @endforeach
                    ],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Daily Revenue' }
                },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Weekly Revenue Chart
        const weeklyRevenueCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
        new Chart(weeklyRevenueCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($revenueWeekly as $data) 'Week {{ $data->week }}', @endforeach
                ],
                datasets: [{
                    label: 'Revenue',
                    data: [
                        @foreach($revenueWeekly as $data) {{ $data->revenue }}, @endforeach
                    ],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Weekly Revenue' }
                },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Monthly Revenue Chart
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($revenueMonthly as $data) 'Month {{ $data->month }}', @endforeach
                ],
                datasets: [{
                    label: 'Revenue',
                    data: [
                        @foreach($revenueMonthly as $data) {{ $data->revenue }}, @endforeach
                    ],
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Monthly Revenue' }
                },
                scales: { y: { beginAtZero: true } }
            }
        });


        const topProducts = @json($topProducts);
        const mostStockedProducts = @json($mostStockedProducts);
        const popularCategories = @json($popularCategories);

        // Biểu đồ Top Selling Products
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: topProducts.slice(0, 5).map(() => ''), // Bỏ tên sản phẩm bên dưới
                datasets: [{
                    label: 'Total Sold',
                    data: topProducts.slice(0, 5).map(product => product.total_sold),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1,
                    barThickness: 'flex' // Tự động điều chỉnh độ rộng để cột sát nhau
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Hiển thị chú thích phía trên
                        labels: {
                            generateLabels: function (chart) {
                                return topProducts.slice(0, 5).map((product, index) => ({
                                    text: product.product_name,
                                    fillStyle: chart.data.datasets[0].backgroundColor[index],
                                    strokeStyle: chart.data.datasets[0].borderColor[index],
                                    hidden: false
                                }));
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Top 5 Selling Products'
                    }
                },
                scales: {
                    x: { display: false }, // Ẩn trục x
                    y: { beginAtZero: true }
                }
            }
        });

        // Biểu đồ Most Stocked Products
        const mostStockedProductsCtx = document.getElementById('mostStockedProductsChart').getContext('2d');
        new Chart(mostStockedProductsCtx, {
            type: 'bar',
            data: {
                labels: mostStockedProducts.slice(0, 5).map(() => ''), // Bỏ tên sản phẩm bên dưới
                datasets: [{
                    label: 'Quantity in Stock',
                    data: mostStockedProducts.slice(0, 5).map(product => product.qty),
                    backgroundColor: [
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1,
                    barThickness: 'flex' // Tự động điều chỉnh độ rộng để cột sát nhau
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Hiển thị chú thích phía trên
                        labels: {
                            generateLabels: function (chart) {
                                return mostStockedProducts.slice(0, 5).map((product, index) => ({
                                    text: product.name,
                                    fillStyle: chart.data.datasets[0].backgroundColor[index],
                                    strokeStyle: chart.data.datasets[0].borderColor[index],
                                    hidden: false
                                }));
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Top 5 Most Stocked Products'
                    }
                },
                scales: {
                    x: { display: false }, // Ẩn trục x
                    y: { beginAtZero: true }
                }
            }
        });




        // Biểu đồ Popular Categories
        const popularCategoriesCtx = document.getElementById('popularCategoriesChart').getContext('2d');
        new Chart(popularCategoriesCtx, {
            type: 'pie',
            data: {
                labels: popularCategories.map(category => category.category_name),
                datasets: [{
                    label: 'Total Sold',
                    data: popularCategories.map(category => category.total_sold),
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)', 'rgba(153, 102, 255, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Popular Categories' }
                }
            }
        });

        const revenueData = @json($revenueShare);
        const deliveryData = @json($deliveryPerformance);
        const reviewsData = @json($vendorReviews);

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'pie',
            data: {
                labels: revenueData.map(v => v.shop_name),
                datasets: [{
                    data: revenueData.map(v => v.revenue),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            },
            options: { responsive: true }
        });

        // Delivery Performance Chart
        const deliveryCtx = document.getElementById('deliveryChart').getContext('2d');
        new Chart(deliveryCtx, {
            type: 'bar',
            data: {
                labels: deliveryData.map(v => v.shop_name),
                datasets: [{
                    label: 'On-Time Deliveries (%)',
                    data: deliveryData.map(v => v.delivery_performance),
                    backgroundColor: '#36A2EB'
                }]
            },
            options: { responsive: true }
        });

        // Reviews Chart
        const reviewsCtx = document.getElementById('reviewsChart').getContext('2d');
        new Chart(reviewsCtx, {
            type: 'bar',
            data: {
                labels: reviewsData.map(v => v.shop_name),
                datasets: [{
                    label: 'Average Rating',
                    data: reviewsData.map(v => v.average_rating),
                    backgroundColor: '#FF6384'
                }]
            },
            options: { responsive: true }
        });
    });
</script>
@endsection