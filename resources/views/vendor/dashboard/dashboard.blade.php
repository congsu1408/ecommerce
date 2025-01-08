@extends('vendor.layouts.master')
@section('title')
{{$settings->site_name}} || Dashboard
@endsection
@section('content')
<section id="wsus__dashboard">
    <div class="container-fluid">
      @include('vendor.layouts.sidebar')
      <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
          <div class="dashboard_content">
            <div class="wsus__dashboard">
              <div class="row">
                <!-- Doanh thu theo thời gian -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Daily Revenue</h4>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>


        <!-- So sánh doanh thu giữa các tháng -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Monthly Revenue</h4>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top sản phẩm bán chạy -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Top Selling Products</h4>
                </div>
                <div class="card-body">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sản phẩm tồn kho nhiều nhất -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Most Stocked Products</h4>
                </div>
                <div class="card-body">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Danh mục sản phẩm bán chạy nhất -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Popular Product Categories</h4>
                </div>
                <div class="card-body">
                    <canvas id="categoriesChart"></canvas>
                </div>
            </div>
        </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Doanh thu theo thời gian
        const revenueDaily = @json($revenueDaily);
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revenueDaily.map(d => d.date),
                datasets: [{
                    label: 'Revenue',
                    data: revenueDaily.map(d => d.revenue),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)'
                }]
            }
        });

        // Tỷ lệ đóng góp doanh thu theo sản phẩm
        const revenueByProduct = @json($revenueByProduct);
        new Chart(document.getElementById('revenueByProductChart'), {
            type: 'pie',
            data: {
                labels: revenueByProduct.map(p => p.product_name),
                datasets: [{
                    data: revenueByProduct.map(p => p.revenue),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            }
        });

        // So sánh doanh thu giữa các tháng
        const revenueMonthly = @json($revenueMonthly);
        new Chart(document.getElementById('monthlyRevenueChart'), {
            type: 'bar',
            data: {
                labels: revenueMonthly.map(m => `Month ${m.month}`),
                datasets: [{
                    label: 'Revenue',
                    data: revenueMonthly.map(m => m.revenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)'
                }]
            }
        });

        // Top sản phẩm bán chạy
        const topProducts = @json($topProducts);
        new Chart(document.getElementById('topProductsChart'), {
            type: 'bar',
            data: {
                labels: topProducts.map(p => p.product_name),
                datasets: [{
                    label: 'Total Sold',
                    data: topProducts.map(p => p.total_sold),
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)'
                }]
            }
        });

        // Sản phẩm tồn kho nhiều nhất
        const mostStockedProducts = @json($mostStockedProducts);
        new Chart(document.getElementById('stockChart'), {
            type: 'bar',
            data: {
                labels: mostStockedProducts.map(p => p.name),
                datasets: [{
                    label: 'Stock',
                    data: mostStockedProducts.map(p => p.qty),
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)'
                }]
            }
        });

        // Danh mục sản phẩm bán chạy nhất
        const popularCategories = @json($popularCategories);
        new Chart(document.getElementById('categoriesChart'), {
            type: 'pie',
            data: {
                labels: popularCategories.map(c => c.category_name),
                datasets: [{
                    data: popularCategories.map(c => c.total_sold),
                    backgroundColor: ['#36A2EB', '#FF6384', '#4BC0C0', '#FFCE56', '#9966FF']
                }]
            }
        });
    });
</script>
@endsection
