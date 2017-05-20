<?php

/* get all themes from ../themes */
function get_all_themes() {

	$mdir = "../themes";
	$cntMods = 0;
	$scanned_directory = array_diff(scandir($mdir), array('..', '.','.DS_Store'));
	$scanned_directory = array_values($scanned_directory);

	return($scanned_directory);
}


/**
 * get directories for Source and Destination
 * $match for source files = src_*
 * $match for destination files = static_*
 */
function get_all_dir_match($dir,$match) {
	
	if(is_dir($dir)) {
		$dirs = glob("$dir/$match", GLOB_BRACE);		
		foreach($dirs as $d) {
				$result[] = basename($d);
		}
	}
	 return $result;
}


/* get all directories and files from src_* */
function generate_index($source) {
	
   $result = array();
   $cdir = scandir($source);
   $scanned_directory = array_diff(scandir($source), array('..', '.','.DS_Store','_assets'));
   foreach ($scanned_directory as $key => $value) {
	   $x++;

     if(is_dir($source . DIRECTORY_SEPARATOR . $value)) {
       $result[$x]['dtitle'] = $value;
       $result[$x]['ftitle'] = '';
       $result[$x]['src'] = $source . DIRECTORY_SEPARATOR . $value;
       $result[$x]['sub'] = generate_index($source . DIRECTORY_SEPARATOR . $value); 
     } else {
       //if($value == 'index.md') {continue;}
       $result[$x]['dtitle'] = '';
       $result[$x]['ftitle'] = $value;
       $result[$x]['src'] = $source . DIRECTORY_SEPARATOR . $value;
       $result[$x]['sub'] = '';
     } 
   } 
   return $result; 
}



/**
 * build sitemap from src files
 * $a array - all files
 */

function build_sitemap($a,$str='') {
	
	$root = $_SESSION['prefix_root'].'/';
	$root = str_replace('//','/',$root);
	$src = $_SESSION['src_dir'].'/';
	ksort($a);
	
	foreach($a as $f => $k) {
		
		unset($parts,$part,$path,$dir);
		
		$dtitle = $k['dtitle'];
		$ftitle = $k['ftitle'];
		$path = $k['src'];		

		if($str == '') {
			/* get page title from index.md */
			$page = parse_md_file($src.'index.md');
			$title = $page['header']['title'];
			$nav = $page['header']['navigation'];
			if($nav == '') {
				$nav = $title;
			}		
					
			$str .= '<li class="nav-main"><a class="'.$class.'" href="'.$root.'" title="'.$title.'"><strong>'.$nav.'</strong></a></li>'."\r\n";			
		}
				
		if(substr($path, -8) == 'index.md') {
			continue;
		}		
		
		$parts = explode('/', $path);
		
		foreach($parts as $part) {
			if(strpos($part, '--') !== false) {
				$part = after_last('--', $part);
			}
			
			if(substr($part, -3) == '.md') {
				$part = str_replace('.md','.html',$part);
				$dir .= $part;
			} else {
				$dir .= $part.'/';
			}
	  		
			$dir = str_replace('//','/',$dir);
			$dir = str_replace("$src","$root",$dir);		
		}
		
		$cnt_slashes = substr_count($path, '/');
		$class = 'nav'.$cnt_slashes;
		$li_class = 'li-'.$cnt_slashes;

		
		if($dtitle != '') {

			/* get page title from index.md */
			$page = parse_md_file($path.'/index.md');
			$title = $page['header']['title'];
			$nav = $page['header']['navigation'];
			if($nav == '') {
				$nav = $title;
			}

			$str .= '<li><ul class="nav">'."\r\n";	
			$str .= '<li><a class="'.$class.'" href="'.$dir.'" title="'.$title.'"><strong>'.$nav.'</strong></a></li>'."\r\n";
		} else {
			
			/* get page title from file */
			$page = parse_md_file($path);
			$title = $page['header']['title'];
			$nav = $page['header']['navigation'];
			if($nav == '') {
				$nav = $dir;
			}			
			
			$str .= '<li><a class="'.$class.'" href="'.$dir.'" title="'.$title.'">'.$nav.'</a></li>'."\r\n";
		}


		
		if(is_array($k['sub'])) {
			$str = build_sitemap($k['sub'],$str);
			$str .= '</ul></li>'."\r\n";
		}
				
	}
	
	return $str;
	
}

/**
 * generate xml sitemap string
 * $a array - all files
 */

function generate_xml_sitemap_string($a) {
	
	$root = $_SESSION['prefix_root'].'/';
	$domain = $_SESSION['prefix_domain'];
	$root = str_replace('//','/',$root);
	$src = $_SESSION['src_dir'].'/';
	ksort($src);

	foreach($a as $f => $k) {
		
		unset($parts,$part,$path,$dir);
		
		$dtitle = $k['dtitle'];
		$ftitle = $k['ftitle'];
		$path = $k['src'];		

		if($str == '') {
			/* get page title from index.md */
			$page = parse_md_file($src.'index.md');
			$title = $page['header']['title'];
			$nav = $page['header']['navigation'];
			if($nav == '') {
				$nav = $title;
			}
			
			
			$str .= '<url>'."\r\n";
			$str .= '<loc>'.$domain.$root.'</loc>'."\r\n";
			$filemtime = filemtime($src.'index.md');
			$str .= '<lastmod>'.date('c', $filemtime).'</lastmod>'."\r\n";
			$str .= '</url>'."\r\n";
		}
				
		if(substr($path, -8) == 'index.md') {
			continue;
		}		
		
		$parts = explode('/', $path);
		
		foreach($parts as $part) {
			if(strpos($part, '--') !== false) {
				$part = after_last('--', $part);
			}
			
			if(substr($part, -3) == '.md') {
				$part = str_replace('.md','.html',$part);
				$dir .= $part;
			} else {
				$dir .= $part.'/';
			}
	  		
			$dir = str_replace('//','/',$dir);
			$dir = str_replace("$src","$root",$dir);		
		}
		
		$cnt_slashes = substr_count($path, '/');
		$class = 'nav'.$cnt_slashes;
		$li_class = 'li-'.$cnt_slashes;

		
		if($dtitle != '') {

			/* get page title from index.md */
			$page = parse_md_file($path.'/index.md');
			$title = $page['header']['title'];
			$nav = $page['header']['navigation'];
			if($nav == '') {
				$nav = $title;
			}

			
			$str .= '<url>'."\r\n";
			$str .= '<loc>'.$domain.$dir.'</loc>'."\r\n";
			$filemtime = filemtime($path.'/index.md');
			$str .= '<lastmod>'.date('c', $filemtime).'</lastmod>'."\r\n";
			$str .= '</url>'."\r\n";
			
		} else {
			
			/* get page title from file */
			$page = parse_md_file($path);
			$title = $page['header']['title'];
			$nav = $page['header']['navigation'];
			if($nav == '') {
				$nav = $dir;
			}			
			
			
			$str .= '<url>'."\r\n";
			$str .= '<loc>'.$domain.$dir.'</loc>'."\r\n";
			$filemtime = filemtime($path);
			$str .= '<lastmod>'.date('c', $filemtime).'</lastmod>'."\r\n";
			$str .= '</url>'."\r\n";
			
		}


		
		if(is_array($k['sub'])) {
			$str = generate_xml_sitemap_string($k['sub'],$str);
		}
				
	}
	
	return $str;
	
}


/**
 * build all static files
 */

function build_files($a) {
	
	foreach($a as $f => $k) {
		
		$dtitle = $k['dtitle'];
		$ftitle = $k['ftitle'];
		$path = $k['src'];
		
		if($ftitle != '') {
			$page = parse_md_file($path);
			save_file($page,$path);
		} elseif($dtitle != '') {
			$page = parse_md_file($path.'/index.md');
			save_file($page,$path.'/index.md');
		}

		
		if(is_array($k['sub'])) {
			build_files($k['sub']);
		}
		
	}
	
}

/**
 * parse .md files
 * get header information between the first two '---'
 * get the remaining content an parse it
 */

function parse_md_file($path) {
	
	global $Parsedown;
	
	if(is_file($path)) {
		$src = file_get_contents($path);
		$src_content = explode('---',$src);
		$header_length = strlen($src_content[1])+6;
		$content = substr($src, $header_length);
		$parsed_header = Spyc::YAMLLoadString($src_content[1]);
		$parsed_content = $Parsedown->text($content);
		$filemtime = filemtime($path);
	} else {
		$parsed = 'FILE NOT FOUND';
	}
	
	$result['header'] = $parsed_header;
	$result['content'] = $parsed_content;
	$result['filemtime'] = $filemtime;
	$result['filename_orig'] = basename($path);
	$result['filepath_orig'] = str_replace('../','',$path);
	
	return $result;
}

/**
 * create needed directories
 * save static html files
 */

function save_file($content,$path) {
	
	global $glob_nav;
	
	$root = $_SESSION['prefix_root'].'/';
	$root = str_replace('//','/',$root);
	$theme = $_SESSION['swg_theme'];
	
	if(is_file('../themes/'.$theme.'/tpl/header.tpl')) {
		$tpl_header = file_get_contents('../themes/'.$theme.'/tpl/header.tpl');
	}
	
	if(is_file('../themes/'.$theme.'/tpl/footer.tpl')) {
		$tpl_footer = file_get_contents('../themes/'.$theme.'/tpl/footer.tpl');
	}
	
	if(preg_match_all('#\<code.*?\>(.*?)\</code\>#', $content['content'], $matches)) {
			$match = $matches[0];
			foreach($match as $k => $v) {
				$o = $match[$k];
				$v = str_replace(array('{','}'),array('&#123','&#125'),$v);
				$content['content'] = str_replace($o, $v, $content['content']);
			}
		}
	
	
	$tpl_file = file_get_contents('../themes/'.$theme.'/tpl/index.tpl');
	$tpl_file = str_replace('{$content}', $content['content'], $tpl_file);
	$tpl_file = str_replace('{$footer}', $tpl_footer, $tpl_file);
	$tpl_file = str_replace('{$header}', $tpl_header, $tpl_file);	
	$tpl_file = str_replace('{$title}', $content['header']['title'], $tpl_file);
	$tpl_file = str_replace('{$description}', $content['header']['description'], $tpl_file);
	$tpl_file = str_replace('{$keywords}', $content['header']['keywords'], $tpl_file);
	$tpl_file = str_replace('{$navigation}', $glob_nav, $tpl_file);
	$tpl_file = str_replace('{$root}', $root, $tpl_file);
	
	$tpl_file = str_replace('{$filename_orig}', $content['filename_orig'], $tpl_file);
	$tpl_file = str_replace('{$filepath_orig}', $content['filepath_orig'], $tpl_file);
	$tpl_file = str_replace('{$filemtime}', $content['filemtime'], $tpl_file);
	$tpl_file = str_replace('{$filemtime_Y}', date("Y",$content['filemtime']), $tpl_file);
	$tpl_file = str_replace('{$filemtime_m}', date("m",$content['filemtime']), $tpl_file);
	$tpl_file = str_replace('{$filemtime_d}', date("d",$content['filemtime']), $tpl_file);
	$tpl_file = str_replace('{$filemtime_H}', date("H",$content['filemtime']), $tpl_file);
	$tpl_file = str_replace('{$filemtime_i}', date("i",$content['filemtime']), $tpl_file);
	$tpl_file = str_replace('{$filemtime_s}', date("s",$content['filemtime']), $tpl_file);
	
	$path_parts = pathinfo($path);
	$filename_new = $path_parts['filename'].'.html';
	$filename_src = $path_parts['filename'].'.md';
	
	if(strpos($filename_new, '--') !== false) {
		$filename_new = after_last('--', $filename_new);
	}
	
	$filepath = $path_parts['dirname'].'/';
	
	$parts = explode('/', $filepath);
	$file = array_pop($parts);
	$dir = $_SESSION['static_destination'];

  foreach($parts as $part) {
	  
	  $d = after_last('--', $part);
	  $dir .= $d.'/';
	  $dir = str_replace('//','/',$dir);
	  
  	if(!is_dir($dir)) {
	  	mkdir($dir,0777);
	  	echo '<div class="alert alert-info">new directory: '.$dir.'</div>';
		}
	}
  
  if(is_file($filepath.$filename_src)) {
	  
	  if(file_put_contents("$dir$filename_new", $tpl_file)) {
		  echo '<div class="alert alert-info">processed file: '.$dir.$filename_new.'</div>';
	  } else {
		  // log error
		  echo '<div class="alert alert-danger">';
		  echo 'ERROR: '.$dir.$filename_new.'<br>';
		  echo '</div>';
		  $_SESSION['cnt_errors']++;
	  }
 
	  
  }
  

}

/* from php.net comments */
function after_last($this, $inthat) {
	if(!is_bool(strrevpos($inthat, $this))) {
		return substr($inthat, strrevpos($inthat, $this)+strlen($this));
	}  
}

function before_last($this, $inthat) {
	return substr($inthat, 0, strrevpos($inthat, $this));
};

function strrevpos($instr, $needle) {
    $rev_pos = strpos (strrev($instr), strrev($needle));
    if ($rev_pos===false) return false;
    else return strlen($instr) - $rev_pos - strlen($needle);
}


function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    if(@mkdir($dst,0777)) {
	    echo '<div class="alert alert-info">new directory: '.$dst.'</div>';
    }
        
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' ) && ( $file != '.DS_Store' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } else { 
                copy($src . '/' . $file,$dst . '/' . $file);
                echo '<div class="alert alert-info">copied file: '.$dst . '/' . $file.'</div>';
            } 
        } 
    } 
    closedir($dir); 
}


function unlinkRecursive($dir, $deleteRootToo) { 
    if(!$dh = @opendir($dir)) { 
        return; 
    } 
    while (false !== ($obj = readdir($dh))) { 
        if($obj == '.' || $obj == '..') { 
            continue; 
        } 

        if (!@unlink($dir . '/' . $obj)) { 
            unlinkRecursive($dir.'/'.$obj, true); 
        } 
    } 

    closedir($dh); 
    
    if ($deleteRootToo) { 
        @rmdir($dir); 
    } 
    
    return; 
} 


?>