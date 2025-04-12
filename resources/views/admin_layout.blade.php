
<!DOCTYPE html>
<head>
<title>Hospital-Chis</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="{{ asset('css/style.css')}}" rel='stylesheet' type='text/css' />
<link href="{{ asset('css/style-responsive.css')}}" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<link rel="stylesheet" href="{{ asset('css/font.css')}}" type="text/css"/>
{{-- <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet">  --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/morris.css')}}" type="text/css"/>
<!-- calendar -->
<link rel="stylesheet" href="{{ asset('css/monthly.css')}}">
<link rel="stylesheet" href="{{ asset('css/form.css')}}">

<!-- //calendar -->
<!-- //font-awesome icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="{{ asset('js/jquery2.0.3.min.js')}}"></script>
<script src="{{ asset('js/raphael-min.js')}}"></script>
<script src="{{ asset('js/morris.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>
<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">
    <a href="{{url('/dashboard')}}" class="logo">
        Hospital
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>

<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <li>
            <input type="text" class="form-control search" placeholder=" Search">
        </li>
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Ảnh">
                @if(Session::has('admin_name'))
                    <span class="username">{{ Session::get('admin_name') }}</span>
                @else
                    <span class="username">Khách</span>
                @endif
    
            
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <li><a href="#"><i class=" fa fa-suitcase"></i>Thông tin</a></li>
                <li><a href="#"><i class="fa fa-cog"></i> Cài đặt</a></li>
                <li><a href="{{ url('/logout')}}"><i class="fa fa-key"></i> Đăng xuất</a></li>
            </ul>
        </li>
    </ul>
</div>
</header>
<aside>
    <div id="sidebar" class="nav-collapse">
        <div class="leftside-navigation">
            @php
                $role = Session::get('role');
            @endphp
            <ul class="sidebar-menu" id="nav-accordion">
                <!-- Mục Tổng quan chỉ hiển thị cho Admin -->
                @if($role == 'admin')
                    <li>
                        <a href="{{ url('/dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Tổng quan</span>
                        </a>
                    </li>
                @endif
    
                <!-- Giấy khám bệnh - Cho cả Admin, Cashier -->
                @if($role == 'admin' || $role == 'cashier')
                    <li>
                        <a href="{{ url('/list-clinic') }}" class="{{ Request::is('list-clinic') ? 'active' : '' }}">Giấy khám bệnh</a>
                    </li>
                @endif
    
                <!-- Phiếu dịch vụ - Cho tất cả các role, trừ Drugist -->
                @if($role == 'admin' || $role == 'doctor' || $role == 'cashier')
                    <li>
                        <a href="{{ url('/list-record-service') }}" class="{{ Request::is('list-record-service') ? 'active' : '' }}">Phiếu dịch vụ</a>
                    </li>
                @endif
    
                <!-- Dịch vụ khám - Chỉ dành cho Admin -->
                @if($role == 'admin')
                    <li>
                        <a href="{{ url('/list-service') }}" class="{{ Request::is('list-service') ? 'active' : '' }}">Dịch vụ khám</a>
                    </li>
                @endif
    
                <!-- Phòng - Chỉ dành cho Admin -->
                @if($role == 'admin')
                    <li>
                        <a href="{{ url('/list-room') }}" class="{{ Request::is('list-room') ? 'active' : '' }}">Phòng</a>
                    </li>
                @endif
    
                <!-- Giường - Chỉ dành cho Admin -->
                @if($role == 'admin')
                    <li>
                        <a href="{{ url('/list-bed') }}" class="{{ Request::is('list-bed') ? 'active' : '' }}">Giường</a>
                    </li>
                @endif
    
                <!-- Kho thuốc - Dành cho Drugist -->
                @if($role == 'admin' || $role == 'druggist')
                    <li>
                        <a href="{{ url('/list-medicine') }}" class="{{ Request::is('list-medicine') ? 'active' : '' }}">Kho thuốc</a>
                    </li>
                @endif
    
                <!-- Đơn thuốc - Dành cho tất cả các role ngoài Drugist -->
                @if($role == 'admin' || $role == 'doctor' || $role == 'cashier')
                    <li>
                        <a href="{{ url('/list-prescription') }}" class="{{ Request::is('list-prescription') ? 'active' : '' }}">Đơn thuốc</a>
                    </li>
                @endif
    
                <!-- Bác sĩ - Dành cho Admin -->
                @if($role == 'admin')
                    <li>
                        <a href="{{ url('/list-doctor') }}" class="{{ Request::is('list-doctor') ? 'active' : '' }}">Bác sĩ</a>
                    </li>
                @endif
    
                <!-- Bệnh nhân - Dành cho tất cả các role -->
                @if($role == 'admin' || $role == 'doctor' || $role == 'cashier')
                    <li>
                        <a href="{{ url('/list-patient') }}" class="{{ Request::is('list-patient') ? 'active' : '' }}">Bệnh nhân</a>
                    </li>
                @endif
    
                <!-- Thẻ BHYT - Dành cho Admin, Cashier -->
                @if($role == 'admin' || $role == 'cashier')
                    <li>
                        <a href="{{ url('/list-bhyt') }}" class="{{ Request::is('list-bhyt') ? 'active' : '' }}">Thẻ BHYT</a>
                    </li>
                @endif
    
                <!-- Thu ngân - Dành cho Cashier -->
                @if($role == 'admin' || $role == 'cashier')
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-book"></i>
                            <span>Thu ngân</span>
                        </a>
                        <ul class="sub">
                            <li><a href="{{ url('/payment') }}" class="{{ Request::is('payment') ? 'active' : '' }}">Thu phí khám</a></li>
                            <li><a href="{{ url('/payment-service') }}" class="{{ Request::is('payment-service') ? 'active' : '' }}">Thu phí dịch vụ</a></li>
                            <li><a href="{{ url('/payment-medicine') }}" class="{{ Request::is('payment-medicine') ? 'active' : '' }}">Nhà thuốc</a></li>
                            <li><a href="{{ url('/payment-patient') }}" class="{{ Request::is('payment-patient') ? 'active' : '' }}">Thu phí nội trú</a></li>
                        </ul>
                    </li>
                @endif
    
            </ul>            
        </div>
    </div>
</aside>
<section id="main-content">
	<section class="wrapper">
		@yield('admin_content')
    </section>
		  <div class="footer">
			<div class="wthree-copyright">
			</div>
		  </div>
</section>
</section>
<script src="{{ asset('js/bootstrap.js')}}"></script>
<script src="{{ asset('js/jquery.dcjqaccordion.2.7.js')}}"></script>
<script src="{{ asset('js/scripts.js')}}"></script>
<script src="{{ asset('js/jquery.slimscroll.js')}}"></script>
<script src="{{ asset('js/jquery.nicescroll.js')}}"></script>
<script src="{{ asset('js/jquery.scrollTo.js')}}"></script>
<script>
	$(document).ready(function() {
	   jQuery('.small-graph-box').hover(function() {
		  jQuery(this).find('.box-button').fadeIn('fast');
	   }, function() {
		  jQuery(this).find('.box-button').fadeOut('fast');
	   });
	   jQuery('.small-graph-box .box-close').click(function() {
		  jQuery(this).closest('.small-graph-box').fadeOut(200);
		  return false;
	   });
	   
	    //CHARTS
	    function gd(year, day, month) {
			return new Date(year, month - 1, day).getTime();
		}
		
		graphArea2 = Morris.Area({
			element: 'hero-area',
			padding: 10,
        behaveLikeLine: true,
        gridEnabled: false,
        gridLineColor: '#dddddd',
        axes: true,
        resize: true,
        smooth:true,
        pointSize: 0,
        lineWidth: 0,
        fillOpacity:0.85,
			data: [
				{period: '2015 Q1', iphone: 2668, ipad: null, itouch: 2649},
				{period: '2015 Q2', iphone: 15780, ipad: 13799, itouch: 12051},
				{period: '2015 Q3', iphone: 12920, ipad: 10975, itouch: 9910},
				{period: '2015 Q4', iphone: 8770, ipad: 6600, itouch: 6695},
				{period: '2016 Q1', iphone: 10820, ipad: 10924, itouch: 12300},
				{period: '2016 Q2', iphone: 9680, ipad: 9010, itouch: 7891},
				{period: '2016 Q3', iphone: 4830, ipad: 3805, itouch: 1598},
				{period: '2016 Q4', iphone: 15083, ipad: 8977, itouch: 5185},
				{period: '2017 Q1', iphone: 10697, ipad: 4470, itouch: 2038},
			
			],
			lineColors:['#eb6f6f','#926383','#eb6f6f'],
			xkey: 'period',
            redraw: true,
            ykeys: ['iphone', 'ipad', 'itouch'],
            labels: ['All Visitors', 'Returning Visitors', 'Unique Visitors'],
			pointSize: 2,
			hideHover: 'auto',
			resize: true
		});
		
	   
	});
	</script>
	<script type="text/javascript" src="{{asset('js/monthly.js')}}"></script>
	<script type="text/javascript">
		$(window).load( function() {

			$('#mycalendar').monthly({
				mode: 'event',
				
			});

			$('#mycalendar2').monthly({
				mode: 'picker',
				target: '#mytarget',
				setWidth: '250px',
				startHidden: true,
				showTrigger: '#mytarget',
				stylePast: true,
				disablePast: true
			});

		switch(window.location.protocol) {
		case 'http:':
		case 'https:':
		break;
		case 'file:':
		alert('Just a heads-up, events will not work when run locally.');
		}

		});
	</script>
</body>
</html>
