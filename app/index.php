<?php
/**
	*	php{swg} 1.0 - static website generator
	* copyright © 2016, Patrick Konstandin <support@flatcore.de>
	*/	

session_start();
error_reporting(0);

require 'functions.php';
require 'lib/Spyc.php';
require 'lib/Parsedown.php';
require 'lang/en.php';


if($_SESSION['static_destination'] == '') {
	$_SESSION['static_destination'] = '../static_en';
}

if($_SESSION['src_dir'] == '') {
	$_SESSION['src_dir'] = '../src_en';
}

if($_SESSION['swg_theme'] == '') {
	$_SESSION['swg_theme'] = 'silver';
}

if($_SESSION['prefix_root'] == '') {
	$_SESSION['prefix_root'] = '/static_en';
}

if($_SESSION['prefix_domain'] == '') {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$_SESSION['prefix_domain'] = $protocol.$_SERVER['HTTP_HOST'];
}

if(isset($_POST['set_src'])) {
	
	$set_src = basename($_POST['set_src']);
	if(is_dir('../'.$set_src)) {
		$_SESSION['src_dir'] = '../'.$set_src;
	}
	$set_dest = basename($_POST['set_dest']);
	if(is_dir('../'.$set_dest)) {
		$_SESSION['static_destination'] = '../'.$set_dest;
	}
	$set_theme = basename($_POST['set_theme']);
	if(is_dir('../themes/'.$set_theme)) {
		$_SESSION['swg_theme'] = $set_theme;
	}
	$set_root = strip_tags($_POST['set_root']);
	$_SESSION['prefix_root'] = $set_root;

	$set_domain = strip_tags($_POST['set_domain']);
	$_SESSION['prefix_domain'] = $set_domain;
	
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>php{swg}</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

		<link rel="stylesheet" href="css/styles.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
		<section id="section_form">
			<div class="container">
			  <div class="text-center">
				  <img src="images/logo.png" class="logo-top">
			  </div>
			<hr class="shadow">

			<?php
			
			$Parsedown = new Parsedown();
			$all_themes = get_all_themes();
			$all_destinations = get_all_dir_match('../','static_*');
			$all_sources = get_all_dir_match('../','src_*');
			
			/* check destination folders */
			foreach($all_destinations as $d) {
				if (!is_writable('../'.$d)) {
					echo '<div class="alert alert-danger">Source folder '.$d.' is not writable</div>';
				}
			}
			
			echo '<div class="row">';
			echo '<div class="col-md-8">';
			
			echo '<fieldset>';
			echo '<legend>'.$lang['legend_prefs'].'</legend>';
			echo '<form id="formPrefs" action="./" method="POST">';
			
			echo '<div class="row">';
				
			/* sources */
			echo '<div class="col-md-6 col-sm-6">';
			echo '<div class="form-group">';
			echo '<label>'.$lang['choose_source'].'</label>';
			echo '<select name="set_src" class="form-control autosubmit">';
			foreach($all_sources as $src) {
				
				$sel = '';
				if($_SESSION['src_dir'] == '../'.$src) { $sel = 'selected'; }
				
				echo '<option value="'.$src.'" '.$sel.'>'.$src.'</option>';
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';
			
			/* destinations */
			echo '<div class="col-md-6 col-sm-6">';
			echo '<div class="form-group">';
			echo '<label>'.$lang['choose_destination'].'</label>';
			echo '<select name="set_dest" class="form-control autosubmit">';
			foreach($all_destinations as $dest) {
				
				$sel = '';
				if($_SESSION['static_destination'] == '../'.$dest) { $sel = 'selected'; }
				
				echo '<option value="'.$dest.'" '.$sel.'>'.$dest.'</option>';
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';

			/* domain */
			echo '<div class="col-md-4">';
			echo '<div class="form-group">';
			echo '<label>'.$lang['label_domain'].'</label>';
			echo '<input type="text" name="set_domain" class="form-control autosubmit" value="'.$_SESSION['prefix_domain'].'">';
			echo '</div>';
			echo '</div>';
						
			/* root */
			echo '<div class="col-md-4">';
			echo '<div class="form-group">';
			echo '<label>'.$lang['label_root'].'</label>';
			echo '<input type="text" name="set_root" class="form-control autosubmit" value="'.$_SESSION['prefix_root'].'">';
			echo '</div>';
			echo '</div>';
			
			/* themes */
			echo '<div class="col-md-4">';
			echo '<div class="form-group">';
			echo '<label>'.$lang['choose_theme'].'</label>';
			echo '<select name="set_theme" class="form-control autosubmit">';
			foreach($all_themes as $theme) {
				
				$sel = '';
				if($_SESSION['swg_theme'] == $theme) { $sel = 'selected'; }
				
				echo '<option value="'.$theme.'" '.$sel.'>'.$theme.'</option>';
			}
			echo '</select>';
			echo '</div>';
			echo '</div>';
		
			echo '</div>'; //row
			

			echo '<input class="btn btn-primary btn-block" type="submit" name="set_prefs" value="'.$lang['submit_prefs'].'">';			


			
			echo '</form>';
			echo '</fieldset>';
			
			echo '</div>';
			echo '<div class="col-md-4">';
			
			echo '<fieldset>';
			echo '<legend>'.$lang['legend_generate'].'</legend>';
			echo '<div class="well">';
			echo '<p>'.$lang['msg_generate'].'</p>';
			
			echo '<form action="./" method="POST">';
			echo '<input class="btn btn-success btn-lg btn-block" type="submit" name="build" value="'.$lang['submit_generator'].'">';
			echo '</form>';
			echo '</div>';
			echo '</fieldset>';
			
			echo '</div>';
			echo '</div>'; //row
			
			echo '<div class="scrollbox results">';
			if(isset($_POST['build'])) {
				$_SESSION['cnt_errors'] = 0;
				
				/* clean destination directory */
				unlinkRecursive($_SESSION['static_destination'],false);
				
				$all_files = generate_index($_SESSION['src_dir']);		
				$glob_nav = build_sitemap($all_files,$str='');
				build_files($all_files);
				
				mkdir($_SESSION['static_destination'].'/themes/',0777);
				recurse_copy('../themes/'.$_SESSION['swg_theme'].'/',$_SESSION['static_destination'].'/themes/'.$_SESSION['swg_theme']);
				recurse_copy($_SESSION['src_dir'].'/_assets/',$_SESSION['static_destination'].'/_assets');
				
				$sitemap = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
				$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";
				$sitemap .= generate_xml_sitemap_string($all_files);
				$sitemap .= '</urlset> ';
				$sitemap_file = $_SESSION['static_destination'].'/sitemap.xml';
				file_put_contents($sitemap_file, $sitemap);
				
				if($_SESSION['cnt_errors'] == 0) {
					echo '<div class="alert alert-success">process completed successfully</div>';
				} else {
					echo '<div class="alert alert-danger"><strong>Error</strong> An error has occurred</div>';
				}
				
			}
			echo '</div>';
			
			?>		

    	</div>
  	</section>
    <footer id="footer">
			<p class="text-center"><img src="images/logo.png" class="logo-footer"><br>PHP Static Website Generator v1.0</p>
		</footer>
    
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

    
  </body>
</html>



