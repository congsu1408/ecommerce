@extends('frontend.dashboard.layouts.master')

@section('title')
{{$settings->site_name}} || Dahsboard
@endsection

@section('content')
<section id="wsus__dashboard">

  <div class="container-fluid">
    @include('frontend.dashboard.layouts.sidebar')
    <div class="row">
      <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
        <h3>User Dashboard</h3>
        <br>
        <div class="wallet-info mb-4">
          <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span><strong>Wallet Balance:</strong></span>
            <span>${{ number_format($walletBalance, 2) }}</span>
          </div>
        </div>
        <div class="dashboard_content">
          <div class="wsus__dashboard">
            <div class="row">

              <!-- Lịch sử mua sắm -->
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Purchase History</h4>
                  </div>
                  <div class="card-body">
                    <canvas id="purchaseHistoryChart"></canvas>
                  </div>
                </div>
              </div>

              <!-- Giá trị mua sắm -->
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Purchase Value</h4>
                  </div>
                  <div class="card-body">
                    <canvas id="purchaseValueChart"></canvas>
                  </div>
                </div>
              </div>

              <!-- Sản phẩm đã mua nhiều nhất -->
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Top Purchased Products</h4>
                  </div>
                  <div class="card-body">
                    <canvas id="topProductsChart"></canvas>
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
    // Lịch sử mua sắm
    const purchaseHistory = @json($purchaseHistory);
    new Chart(document.getElementById('purchaseHistoryChart'), {
      type: 'line',
      data: {
        labels: purchaseHistory.map(p => p.date),
        datasets: [{
          label: 'Total Orders',
          data: purchaseHistory.map(p => p.total_orders),
          borderColor: 'rgba(54, 162, 235, 1)',
          backgroundColor: 'rgba(54, 162, 235, 0.2)'
        }]
      }
    });

    // Giá trị mua sắm
    const purchaseValue = @json($purchaseValue);
    new Chart(document.getElementById('purchaseValueChart'), {
      type: 'line',
      data: {
        labels: purchaseValue.map(p => p.date),
        datasets: [{
          label: 'Total Spent ($)',
          data: purchaseValue.map(p => p.total_spent),
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)'
        }]
      }
    });

    // Sản phẩm đã mua nhiều nhất
    const topPurchasedProducts = @json($topPurchasedProducts);
    new Chart(document.getElementById('topProductsChart'), {
      type: 'bar',
      data: {
        labels: topPurchasedProducts.map(p => p.name),
        datasets: [{
          label: 'Total Quantity',
          data: topPurchasedProducts.map(p => p.total_quantity),
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          borderColor: 'rgba(255, 159, 64, 1)'
        }]
      }
    });
  });
</script>
@endsection