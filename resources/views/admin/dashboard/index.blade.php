@extends('layouts.app')

@section('page_css')
<link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
<link rel="stylesheet" href="{{ asset('css/owl.theme.css') }}">
@endsection

@section('css')
<style>
    .card-body.p-0.review {
        max-height: 335px;
        overflow-y: scroll;
    }

    .media .media-title {
        max-width: 220px;
    }
</style>
@endsection

@section('title')
    {{ $title }}
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-stats">
                <div class="card-stats-title">Statistik Order -
                    <div class="dropdown d-inline">
                        <span class="font-weight-600">{{ getMonth(date('Y-m-d'))}}</span>
                        <span class="float-right"><a href="{{ route('admin.orders.index') }}">Lihat Selengkapnya <i class="fas fa-chevron-right"></i></a></span>
                    </div>
                </div>
                <div class="card-stats-items">
                    <div class="card-stats-item">
                    <div class="card-stats-item-count">{{ $countMonthlyOrders['pending'] }}</div>
                    <div class="card-stats-item-label">Pending</div>
                    </div>
                    <div class="card-stats-item">
                    <div class="card-stats-item-count">{{ $countMonthlyOrders['received'] }}</div>
                    <div class="card-stats-item-label">Diterima</div>
                    </div>
                    <div class="card-stats-item">
                    <div class="card-stats-item-count">{{ $countMonthlyOrders['delivered'] }}</div>
                    <div class="card-stats-item-label">Dikirim</div>
                    </div>
                    <div class="card-stats-item">
                    <div class="card-stats-item-count">{{ $countMonthlyOrders['completed'] }}</div>
                    <div class="card-stats-item-label">Selesai</div>
                    </div>
                    <div class="card-stats-item">
                    <div class="card-stats-item-count">{{ $countMonthlyOrders['canceled'] }}</div>
                    <div class="card-stats-item-label">Dibatalkan</div>
                    </div>
                </div>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                <i class="fas fa-archive"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Order</h4>
                </div>
                <div class="card-body">
                    {{ $countMonthlyOrders['all'] }}
                </div>
                </div>
            </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-chart">
                <canvas id="balance-chart" height="80"></canvas>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Penjualan</h4>
                </div>
                <div class="card-body">
                    {{ convert_to_rupiah($monthlySellingPrice) }}
                </div>
                </div>
            </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-chart">
                <canvas id="sales-chart" height="80"></canvas>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="card-wrap">
                <div class="card-header">
                    <h4>Produk Terjual</h4>
                </div>
                <div class="card-body">
                    {{ $monthlySellingProduct }}
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                <h4>Order 7 Hari Terakhir</h4>
                </div>
                <div class="card-body">
                <canvas id="myChart" height="119"></canvas>
                </div>
            </div>
            </div>
            <div class="col-lg-4">
                <div class="card gradient-bottom">
                    <div class="card-header">
                        <h4>Produk Terlaris</h4>
                    </div>
                    <div class="card-body" id="top-5-scroll">
                    <ul class="list-unstyled list-unstyled-border">
                        @foreach($topSellingProduct as $product)
                            <li class="media">
                                <img class="mr-3 rounded" width="55" src="{{ $product->thumb.$product->image1 }}" alt="{{ $product->name }}">
                                <div class="media-body">
                                    <div class="float-right">
                                        <div class="font-weight-600 text-muted text-small">
                                            Terjual {{ $product->sum_product }}
                                        </div>
                                    </div>
                                    <div class="media-title">
                                        <a href="#">
                                            {{ substr($product->name, 0, 45) }} {{ strlen($product->name) > 45 ? "..." : "" }}
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    </div>
                    <div class="card-footer pt-3 d-flex justify-content-center">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Produk Terbaik</h4>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="products-carousel">
                            @forelse ($bestSellingProduct as $item)
                                <div class="item">
                                    <div class="product-item pb-3">
                                        <div class="product-image">
                                            <img alt="{{ $item->name }}" src="{{ asset($item->thumb.$item->image1) }}" class="img-fluid">
                                        </div>
                                        <div class="product-details">
                                            <div class="product-name">
                                                {{ substr($item->name, 0, 50) }} {{ strlen($item->name) > 50 ? "..." : "" }}
                                            </div>
                                            <div class="product-review">
                                                <i class="fas fa-star"></i>
                                                {{ number_format($item->avg_rating, 1) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="item text-center">
                                    <p>No Data Available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                <h4>Order Terbaru</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.orders.index') }}">Lihat Selengkapnya <i class="fas fa-chevron-right"></i></a>
                </div>
                </div>
                <div class="card-body p-0">
                <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                    <tr>
                        <th>Invoice ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($getRecentOrder as $order)
                    <tr>
                        <td><a href="#">{{ $order->code }}</a></td>
                        <td class="font-weight-600">{{ $order->users->first_name }} {{ $order->users->last_name }}</td>
                        <td>{!! getOrderStatusMember($order->status) !!}</td>
                        <td>{{ getDateTimeIndo($order->order_date) }}</td>
                        <td>
                        <a href="{{ route('admin.orders.detail', simple_encrypt($order->id)) }}" class="btn btn-primary">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                    </table>
                </div>
                </div>
            </div>
            </div>
            <div class="col-md-4">
                <div class="card card-hero">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="far fa-comments"></i>
                        </div>
                        <h5>Ulasan Produk Terbaru</h5>
                    </div>
                    <div class="card-body p-0 review">
                        <div class="tickets-list">
                            @forelse ($getRecentReview as $item)
                                <a href="" onclick="return false" class="ticket-item">
                                    <div class="ticket-title">
                                        <h4>{{ $item->message }}</h4>
                                    </div>
                                    <div class="ticket-info">
                                        <div>{{ $item->users->first_name }} {{ $item->users->last_name }}</div>
                                        <div class="bullet"></div>
                                        <div class="text-primary">{{ $item->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @empty
                                <a href="" onclick="return false" class="ticket-item">
                                    <div class="ticket-title">
                                        <h4>Belum ada Ulasan</h4>
                                    </div>
                                </a>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_js')

<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
@endsection

@section('scripts')
<script>

var ctx = document.getElementById("myChart").getContext('2d');
let date = @json($weeklyOrder['date']);
let count = @json($weeklyOrder['count']);
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: date,
        datasets: [{
          label: 'Sales',
          data: count,
          borderWidth: 2,
          backgroundColor: 'rgba(63,82,227,.8)',
          borderWidth: 0,
          borderColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius: 3.5,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
        }]
    },
    options: {
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          gridLines: {
            // display: false,
            drawBorder: false,
            color: '#f2f2f2',
          },
        }],
        xAxes: [{
          gridLines: {
            display: false,
            tickMarkLength: 15,
          }
        }]
      },
    }
});

var balance_chart = document.getElementById("balance-chart").getContext('2d');

var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

var myChart = new Chart(balance_chart, {
  type: 'line',
  data: {
    labels: ['16-07-2018', '17-07-2018', '18-07-2018', '19-07-2018', '20-07-2018', '21-07-2018', '22-07-2018', '23-07-2018', '24-07-2018', '25-07-2018', '26-07-2018', '27-07-2018', '28-07-2018', '29-07-2018', '30-07-2018', '31-07-2018'],
    datasets: [{
      label: 'Balance',
      data: [50, 61, 80, 50, 72, 52, 60, 41, 30, 45, 70, 40, 93, 63, 50, 62],
      backgroundColor: balance_chart_bg_color,
      borderWidth: 3,
      borderColor: 'rgba(63,82,227,1)',
      pointBorderWidth: 0,
      pointBorderColor: 'transparent',
      pointRadius: 3,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(63,82,227,1)',
    }]
  },
  options: {
    layout: {
      padding: {
        bottom: -1,
        left: -1
      }
    },
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          beginAtZero: true,
          precision: 0,
          suggestedMin: 0,
          display: false
        }
      }],
      xAxes: [{
        gridLines: {
          drawBorder: false,
          display: false,
        },
        ticks: {
          display: false
        }
      }]
    },
  }
});

var sales_chart = document.getElementById("sales-chart").getContext('2d');

var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

var myChart = new Chart(sales_chart, {
  type: 'line',
  data: {
    labels: ['16-07-2018', '17-07-2018', '18-07-2018', '19-07-2018', '20-07-2018', '21-07-2018', '22-07-2018', '23-07-2018', '24-07-2018', '25-07-2018', '26-07-2018', '27-07-2018', '28-07-2018', '29-07-2018', '30-07-2018', '31-07-2018'],
    datasets: [{
      label: 'Sales',
      data: [70, 62, 44, 40, 21, 63, 82, 52, 50, 31, 70, 50, 91, 63, 51, 60],
      borderWidth: 2,
      backgroundColor: balance_chart_bg_color,
      borderWidth: 3,
      borderColor: 'rgba(63,82,227,1)',
      pointBorderWidth: 0,
      pointBorderColor: 'transparent',
      pointRadius: 3,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(63,82,227,1)',
    }]
  },
  options: {
    layout: {
      padding: {
        bottom: -1,
        left: -1
      }
    },
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          beginAtZero: true,
          display: false
        }
      }],
      xAxes: [{
        gridLines: {
          drawBorder: false,
          display: false,
        },
        ticks: {
          display: false
        }
      }]
    },
  }
});

$("#products-carousel").owlCarousel({
  nav: false,
  navText: ['', ''],
  lazyload: true,
  slidespeed: 500,
  items: 5,
  margin: 15,
  autoplay: true,
  autoplayTimeout: 3000,
  loop: true,
  responsive: {
    0: {
      items: 3
    },
    768: {
      items: 3
    },
    1200: {
      items: 4
    }
  }
});
</script>
@endsection
