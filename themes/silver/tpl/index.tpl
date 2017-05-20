<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>{$title}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="{$description}" />
	  <meta name="keywords" content="{$keywords}" />
	  
	  <link rel="stylesheet" href="{$root}themes/silver/css/bootstrap.min.css">
	  <link rel="stylesheet" href="{$root}themes/silver/css/styles.css">
	</head>
	<body>
		<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".sidebar-nav">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
			
				<div class="row row-offcanvas row-offcanvas-left">
					<div class="col-sm-4 col-md-3 sidebar sidebar-offcanvas" id="sidebar" role="navigation">
						<ul class="nav nav-sidebar">
							{$navigation}
						</ul>
					</div>
					<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3 main">
											
						{$header}
						
						<div id="page-content">
							
							{$content}
							
						</div>
						
						{$footer}
					</div>
				</div>
			
		</div>

    <!-- jQuery -->
    <script src="{$root}themes/silver/js/jquery-1.12.0.min.js"></script>
    <!-- bootstrap -->
    <script src="{$root}themes/silver/js/bootstrap.min.js"></script>
    <!-- theme/silver -->
    <script src="{$root}themes/silver/js/theme.js"></script>	
	</body>
</html>