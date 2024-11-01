<?php
/*
Plugin Name: Page Jump
Plugin URI: http://charlesstricklin.com/2005/07/23/page-jump-plug-in/
Description: Allows jumping to particular pages
Version: 1.0.1b
Author: <a href="http://charlesstricklin.com">Charles W. Stricklin</a> and <a href="http://xmouse.ithium.net/">David House</a>
Author URI: http://charlesstricklin.com

These plug-in's code is released under GPL:
http://www.opensource.org/licenses/gpl-license.php

*/

	function display_page_link ($pagenum, $curpage, $endpage) {
		$display_page_link = "";
		if ($curpage == $pagenum) {
			$display_page_link = $display_page_link . '<span class="this-page">' . $pagenum . '</span>' . "\n";
		} else {
			if ($pagenum == $endpage || $pagenum == 1) {
				$display_page_link = $display_page_link . sprintf('<a href="%s/page/%d" class="end">%d</a>', $blog, $pagenum, $pagenum) . "\n";
			} else {
				if ($pagenum == 1) {
					$display_page_link = $display_page_link . sprintf('<a href="%s">%d</a>', $blog, $pagenum) . "\n";
				} else {
					$display_page_link = $display_page_link . sprintf('<a href="%s/page/%d">%d</a>', $blog,  $pagenum, $pagenum) . "\n";
				}
			}
		}
		return $display_page_link;
	}
	
	function page_jump ( ) {
	
		global $wpdb, $posts_jump_output;
		
		if ($posts_jump_output) {
			echo $posts_jump_output;
			return;
		}
		
		$posts_per_page = ($matches[1]) ? $matches[1] : get_option('posts_per_page');
		$now = gmdate('Y-m-d H:i:59');
		
		$numposts = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = 'publish' AND post_date_gmt <= '$now'");
		if (0 < $numposts) $numposts = number_format($numposts);
		$maxpages = ceil($numposts / $posts_per_page);
	
		$this_page = $_GET['paged'];
		if ($this_page < 2) $this_page = 1;
	
		if ($maxpages < 15) {
		
			for ($i = 1; $i <= $maxpages; $i++)
				$posts_jump_output .= display_page_link($i, $this_page, $maxpages);
		
		} elseif ($maxpages >= 15 && ($this_page <= 3 || $this_page >= ($maxpages - 5))) {
			
			$posts_jump_output .= display_page_link(1, $this_page, $maxpages);
			$posts_jump_output .= display_page_link(2, $this_page, $maxpages);
			$posts_jump_output .= display_page_link(3, $this_page, $maxpages);
		
			$posts_jump_output = $posts_jump_output .  '...';
			
			if ($this_page > 3 && ($maxpages - $this_page) >= 3) {
				//display all the pages from $this_page up to $maxpages
				for ($i = $this_page; $i <= $maxpages; $i++) {
					$posts_jump_output .= display_page_link($i, $this_page, $maxpages);
				}
			} else {
				//display the top 3
				$posts_jump_output .= display_page_link($maxpages - 2, $this_page, $maxpages);
				$posts_jump_output .= display_page_link($maxpages - 1, $this_page, $maxpages);
				$posts_jump_output .= display_page_link($maxpages, $this_page, $maxpages);
			}
		
		} elseif ($maxpages >= 15) {
		
			$posts_jump_output .= display_page_link(1, $this_page, $maxpages);
			$posts_jump_output .= display_page_link(2, $this_page, $maxpages);
			$posts_jump_output .= display_page_link(3, $this_page, $maxpages);
			
			$posts_jump_output .= '...' . "\n";
			
			$posts_jump_output .= display_page_link($this_page - 2, $this_page, $maxpages);
			$posts_jump_output .= display_page_link($this_page - 1, $this_page, $maxpages);
			$posts_jump_output .= display_page_link($this_page, $this_page, $maxpages);
			$posts_jump_output .= display_page_link($this_page + 1, $this_page, $maxpages);
			$posts_jump_output .= display_page_link($this_page + 2, $this_page, $maxpages);
			
			$posts_jump_output .= '...' . "\n";
			
			$posts_jump_output .= display_page_link($maxpages - 2, $this_page, $maxpages);
			$posts_jump_output .= display_page_link($maxpages - 1, $this_page, $maxpages);
			$posts_jump_output .= display_page_link($maxpages, $this_page, $maxpages);
		
		}
		echo $posts_jump_output;
	} // end page_jump()

?>