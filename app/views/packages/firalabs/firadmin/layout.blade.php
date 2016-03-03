<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="上海概瑞信息技术有限公司 http://bigecko.com">

    {{ HTML::style('vendor/bootstrap2.3.2/css/bootstrap.min.css') }}
    {{ HTML::style('vendor/bootstrap2.3.2/css/bootstrap-responsive.min.css') }}
    {{ HTML::style('packages/cms/css/admin.css') }}
</head>

<body>

	<?php if(Request::segment(2) !== 'login'):?>
	<!-- Main navigation -->
	<div class="navbar navbar-fixed-top <?php echo Config::get('firadmin::navigation_inverse')?'navbar-inverse':''?>">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="<?php echo URL::to(Config::get('firadmin::project_url'))?>"><?php echo $project_name?></a>
				<div class="nav-collapse collapse">
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-user <?php echo Config::get('firadmin::navigation_inverse')?'icon-white':''?>"></span> <?php echo !empty(Auth::user()->username)?Auth::user()->username:''?> <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo URL::to(Config::get('firadmin::route.user') . '/' .  (!empty(Auth::user()->id)?Auth::user()->id:''))?>"><?php echo Lang::get('firadmin::admin.profile')?></a></li>
								<li><a href="<?php echo URL::to(Config::get('firadmin::route.logout'));?>"><?php echo Lang::get('firadmin::admin.logout')?></a></li>
               				</ul>
              			</li>
					</ul>
					<ul class="nav">
						<?php foreach ($navigation as $uri => $title):?>
						<li <?php echo ($active_menu == $uri)?'class="active"':'';?>><a href="<?php echo URL::to($uri);?>"><?php echo $title;?></a></li>
						<?php endforeach;?>
						
					</ul>
					
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<?php endif;?>
  
  	{{$content}}

    @yield('body')

@section('scripts')
  {{ HTML::script('vendor/jquery-1.11.1.min.js') }}
  {{ HTML::script('vendor/bootstrap2.3.2/js/bootstrap.min.js') }}
  <script>{{ JS::renderObj('app') }}</script>
  {{ HTML::script('js/larapp.js') }}
  {{ JS::renderScripts() }}
  {{ HTML::script('packages/cms/js/ajaxCommon.js') }}
@show
</body>
</html>