<!DOCTYPE html>
<html lang="">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ isset( $title ) ? $title - DCMS | Admin : 'DCMS | Admin' }}</title>

<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/style.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('packages/dcweb/dcms/assets/css/font-awesome.min.css') }}">
<link rel="shortcut icon" href="/favicon.ico" />

<script type="text/javascript" src="{{ asset('packages/dcweb/dcms/assets/js/jquery-1.10.2.js') }}"></script>
<!--
<script type="text/javascript" src="js/jquery-ui/jquery-ui-1.10.4.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui-1.10.4.custom.min.css" />
--> 

<style type="text/css">
</style>

<script type="text/javascript">
$(document).ready(function() {

	$(".sidebar .dropdown > a").click(function() {
		$(this).closest(".dropdown").toggleClass("open");
		return false;
	});

	$("#sidebar-collapse").click(function() {
		$(".main-wrapper").toggleClass("sidebar-collapsed");
		$(this).find("i").toggleClass("fa-angle-left").toggleClass("fa-angle-right");
	});
	
	$(window).scroll(function() {
		if ( $(this).scrollTop() > 10) {
			$(".back-to-top").show('fast');
		} else {
			$(".back-to-top").hide();
		}
	});
	
/*
	$(window).resize(function() {
		if ( $(this).width() < 960 ) {
			$(".main-wrapper").addClass("sidebar-collapsed");
			$("#sidebar-collapse i").removeClass("fa-angle-left").addClass("fa-angle-right");
		} else {
			$(".main-wrapper").removeClass("sidebar-collapsed");
			$("#sidebar-collapse i").addClass("fa-angle-left").removeClass("fa-angle-right");
		}
	});
*/

});
</script>

</head>
<body>

@include("dcms::template/navbar")

<div class="main-wrapper sidebar-collapsed">

@include("dcms::template/sidebar")

  <div class="container-fluid main">

@yield("content")

  </div>

</div>
<a class="back-to-top" href="#"><i class="fa fa-angle-up"></i></a>

@yield("script")

</body>
</html>