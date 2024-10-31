<?php

/**
 * Plugin Name: Post and Taxonomy Filter
 * Version: 0.1
 * Plugin URI: https://wordpress.org/plugins/post-and-taxonomy-filter/
 * Description: Post and Taxonomy jQuery or Ajax Filter
 * Author: M.Yasir Hussain
 * Author URI: https://www.yasglobal.com/web-design-development/wordpress/post-taxonomy-filter/
 * Text Domain: post-and-taxonomy-filter
 * License: GPLv3
 */
 
 /**
 *  Post and Taxonomy Filter Plugin
 *  Copyright (C) 2018, M.Yasir Hussain <yasir.1989@ymail.com>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.

 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 


// Add settings link on plugin page
function post_taxonomy_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=post-taxonomy-settings">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}

function post_taxonomy_menu() {
   $page = add_menu_page('Post Filter', 'Post Filter', 'administrator', 'post-taxonomy-settings', 'post_taxonomy_settings_page', plugins_url('/post-taxonomy-filter.png', __FILE__) , 10 );

	//call register settings function

   add_action( 'admin_init', 'register_post_taxonomy_settings' );
}



function register_post_taxonomy_settings() {
	register_setting( 'post-taxonomy-settings-group', 'post_taxonomy_post_type' );
	register_setting( 'post-taxonomy-settings-group', 'post_taxonomy_post_taxonomy' );
	register_setting( 'post-taxonomy-settings-group', 'post_taxonomy_filter' );
	register_setting( 'post-taxonomy-settings-group', 'post_taxonomy_query' );
	register_setting( 'post-taxonomy-settings-group', 'post_taxonomy_row_post' );
	register_setting( 'post-taxonomy-settings-group', 'post_taxonomy_html' );
	register_setting( 'post-taxonomy-settings-group', 'post_html' );
	

} 

function post_taxonomy_settings_page() {
?>
      <h2 class="title-ptf">Post and Taxonomy Filter</h2>
	  <hr/>
	  <div class="about-ptf">
		Paste Shortcode any Where inside the Content or Template: <span>[post_taxonomy_filters]</span></div>
	  <hr/>		 
      <form method="post" action="options.php"> 
         <?php
            settings_fields( 'post-taxonomy-settings-group' );
            do_settings_sections( 'post-taxonomy-settings-group' );
         ?>
		 <input type="hidden" name="post_taxonomy_post_type" value="<?php echo esc_attr( get_option('post_taxonomy_post_type') );?>"/>
		 <input type="hidden" name="post_taxonomy_post_taxonomy" value="<?php echo esc_attr( get_option('post_taxonomy_post_taxonomy') );?>"/>
		 <input type="hidden" name="post_taxonomy_filter" value="<?php echo esc_attr( get_option('post_taxonomy_filter') );?>"/>
		 <input type="hidden" name="post_taxonomy_query" value="<?php echo esc_attr( get_option('post_taxonomy_query') );?>"/>
	    
		 <div class="basicsetting">
		 <h3>Basic Setting:</h3>
	      	     
		  <?php
		    
       		    $post_types = get_post_types();
	        /*    print '<pre>';
	            print_r($post_types);
	            print '</pre>';
			*/
	      ?>
		<div class="section-detail">
		  <div class="setting-section">Post Type: <?php //echo esc_attr( get_option('post_taxonomy_post_type') );?>
	      <select name="ptf_post_type" class="post_taxonomy_post_type" >
	           <option value="null">Select Post Type</option>
			   <?php
	           foreach($post_types as $post_typess){
	            	if( $post_typess!='attachment' && $post_typess!='revision' && $post_typess!='nav_menu_item' && $post_typess!='custom_css' && $post_typess!='acf' && $post_typess!='mt_pp' && $post_typess!='customize_changeset' ){
		         	     print '<option value="'.$post_typess.'">'.$post_typess.'</option>';
	         	     }
	           }
			   ?>
	      </select>
		  </div>
	
		
	      <?php 
		  
		       $post_taxonomy =get_taxonomies();
	         /*  print '<pre>';
	           print_r($post_taxonomy);
	           print '</pre>';
			*/
		  ?>
		<div class="setting-section">Post Taxonmy:<?php //echo esc_attr( get_option('post_taxonomy_post_taxonomy') );?>
	      <select name="ptf_tax_type" class="post_taxonomy_post_taxonomy" >
	         <option value="null">Select Taxonomy</option>
			 <?php
	        foreach($post_taxonomy as $post_taxonomy){
		       if( $post_taxonomy!='nav_menu' && $post_taxonomy!='link_category' && $post_taxonomy!='post_format' ){
			       print '<option value="'.$post_taxonomy.'">'.$post_taxonomy.'</option>';
		       }
	       }
		   ?>
	     </select>			
		 </div>
	</div>
	</div>
		<hr/>		
 <div class="themesetting basicsetting">
		<h3>Theme Setting:</h3>
	<div class="section-detail">
	<div class="setting-section">
		<div class="filter-show">
		
			Show Filter Where: 
			<input type="radio" name="filter_show" value="top" class="post_taxonomy_filter">
			<label>Top</label>
			<input type="radio" name="filter_show" checked value="left" class="post_taxonomy_filter">
			<label>Left</label>
			<input type="radio" name="filter_show" value="right" class="post_taxonomy_filter">
			<label>Right</label>
		</div>
		</div>
		<div class="setting-section">
		<div class="row-post">
		Show Post in a Row: 
		<?php $row_post = esc_attr( get_option('post_taxonomy_row_post') );
		if($row_post == ''){
			$row_post =2;
		}
		?>
		<input type="number" name="post_taxonomy_row_post" min="1" value="<?php echo $row_post;?>"/>
		</div>
		</div>
	</div>	
	</div>
		<hr/>
		<div class="working-query themesetting basicsetting">
		<h3>Work Query as:</h3>
		<div class="section-detail">
		<input type="radio" name="query_work" checked value="jQuery" class="post_taxonomy_query">
		<label>Jquery</label>
		<input type="radio" name="query_work" value="Ajax" class="post_taxonomy_query">
		<label>Ajax</label>
		</div>
		</div>
		
		<hr/>
		
		
		<div class="show-output">
		<h3 class="output-heading" >Output:</h3>
		<hr>
		<div>
		<div class="filter-description">
			<h4><b>Filters:</b> For Taxonomy</h4>
				Name: [tax_name]<br/>
				Permalink: [tax_link]<br/>
				Custom Field: [tax_custom_<b>name</b>_field]<br/>
		</div>
		<div class="filter-taxonomy">
			<h4><b>Output:</b> Taxonomy Filter Html</h4>
			<?php $tax_html = esc_attr( get_option('post_taxonomy_html') );
					if($tax_html == ''){
						$tax_html = '<div class="filter">[tax_name]</div>';
					}
			?>
				<textarea name="post_taxonomy_html"><?php echo $tax_html;?>
				</textarea>
			</div>
		
		</div>
		
		<hr>
			<div class="filter-description">
			<h4><b>Filters:</b> For Post</h4>
			
				Image: [image]<br/>
				Title: [title]<br/>
				Post Date: [post_date]<br/>
				Excerpt: [excerpt]<br/>
				Content: [content]<br/>				
				Permalink: [post_link]<br/>
				Author Name: [author_name]<br/>
				Author Image: [author_image]<br/>
				Author Link: [author_link]<br/>				
				Custom Field: [post_custom_<b>name</b>_field]<br/>
			</div>
			
			
			<div class="post_output">
			<h4><b>Output:</b> Post Filter Html</h4>
			<?php $post_html = esc_attr( get_option('post_html') );
					if($post_html == ''){
						$post_html = '<div class="post-image">[image]</div>
<div class="other-details">
        <h4>[title]</h4>
	<div class="about-post">
             <div class="post-author">[author_image][author_name]</div>
             <div class="post-date">[post_date]</div>
       </div>
       <div class="description">[excerpt]</div>
<a href="[post_link]" title="[title]">Read More</a>
</div>';
					}
			?>
				<textarea name="post_html"><?php echo $post_html; ?>
				</textarea>
			</div>
			
		</div>
		<hr>
         <?php submit_button(); ?>
      </form>
<?php 


} 



if (function_exists("add_action") && function_exists("add_filter")) {
   $plugin = plugin_basename(__FILE__); 
   add_filter("plugin_action_links_$plugin", 'post_taxonomy_settings_link' );

   add_action('admin_menu', 'post_taxonomy_menu');
}


#-----------------------------------------------------------------#
# Show Filter Data Shortcode [post_taxonomy_filters]
#-----------------------------------------------------------------# 
function post_taxonomy_filters(){	
		global $wpdb;		
	    $post_taxonomy_filters = "";
		/*
		echo esc_attr( get_option('post_taxonomy_post_type') );
		echo '<br>==================================<br>';
		echo esc_attr( get_option('post_taxonomy_post_taxonomy') );
		echo '<br>==================================<br>';
		echo esc_attr( get_option('post_taxonomy_filter') );
		echo '<br>==================================<br>';
		echo esc_attr( get_option('post_taxonomy_query'));
		echo '<br>==================================<br>';
		echo esc_attr( get_option('post_taxonomy_row_post'));
		echo '<br>==================================<br>';
		echo esc_attr( get_option('post_html'));
		echo '<br>==================================<br>';
		*/
		?>
		
		<?php
		if(esc_attr( get_option('post_taxonomy_post_taxonomy') ) != ''){
			$taxonomy_name = esc_attr( get_option('post_taxonomy_post_taxonomy') );
		}else{
			$taxonomy_name = 'category';
		}
    	$terms = $wpdb->get_results('SELECT DISTINCT(tt.term_id), t.name, t.slug, tt.taxonomy, tt.description FROM wp_terms t INNER JOIN wp_term_taxonomy tt ON tt.term_id = t.term_id WHERE tt.taxonomy = "'.$taxonomy_name.'"');?>
			<?php if (!empty($terms)) {
			$rn = -1;
		foreach ($terms as $term) {					 
				$rn++;
				$rn_active = "";
			if($rn == 0){$rn_active = " active";}
			   $cat_link    = get_term_link( $term->slug, get_option('post_taxonomy_post_taxonomy'));
			   
				$html_tax = get_option('post_taxonomy_html');
				$html_tax = str_replace("[tax_name]",$term->name,$html_tax);
				$html_tax = str_replace("[tax_link]",$cat_link,$html_tax);
				
				$cf_break = explode("[tax_custom_",$html_tax);
				//print sizeof($cf_break);]
				if(sizeof($cf_break) > 0){
					foreach ($cf_break as $cfs){
						//print $cfs;
						$cf_breaks = explode('_field]',$cfs);
						//print $cf_breaks[0].'<br>';
						$field_array = get_field($cf_breaks[0],esc_attr( get_option('post_taxonomy_post_taxonomy') ).'_'.$term->term_id);
						//print_r($field_array);
						
						if(is_array($field_array)){
							$field_array = '<img src="'.$field_array['url'].'" alt="'.$field_array['alt'].'" />';
						}
						$html_tax = str_replace('[tax_custom_'.$cf_breaks[0].'_field]',$field_array,$html_tax);
					
					}
				}
				
                $post_taxonomy_filters .= '<li class="region-name '.$term->slug.''.$rn_active.'" title="'.$term->name.'"  data-id="'.$term->term_id.'" data-type="'.$term->slug.'">';
					$post_taxonomy_filters .= $html_tax;
                $post_taxonomy_filters .= '</li>';
           }
		}
		
		?>
		
		<div class="filter-resullts-area <?php echo esc_attr( get_option('post_taxonomy_filter') );?>" data-prefix="<?php echo $wpdb->prefix;?>" data-admin="<?php echo admin_url();?>">
			
			<div class="filter-section">
			   <?php echo '<ul>'.$post_taxonomy_filters.'</ul>'; ?>
			</div>
			
			<div class="filtered-content-section box-<?php echo esc_attr( get_option('post_taxonomy_row_post'));?>">
		
		<?php //echo esc_attr( get_option('post_taxonomy_query') );?>
		<div class="region-vendors">

                <?php 
				$loop = count($terms);
				if(esc_attr( get_option('post_taxonomy_query') ) == 'Ajax'){
					$loop = 1;
				}
			for($v=0;$v<$loop;$v++){
			$vd_active = "";
			if($v == 0){$vd_active = "active";}
		?>
                <div class="region-vendor <?php echo $vd_active;?>" data-region="<?php echo $terms[$v]->slug;?>">

                    <?php 
					if(esc_attr( get_option('post_taxonomy_post_type') ) != ''){
						$post_type_name = esc_attr( get_option('post_taxonomy_post_type') );
					}else{
						$post_type_name = 'post';
					}
					
					$vendor_data = $wpdb->get_results('SELECT DISTINCT(p.ID) , p.post_title,p.post_excerpt,p.post_content,p.post_date, p.guid FROM wp_posts p INNER JOIN wp_term_relationships tr ON p.ID = tr.object_id INNER JOIN wp_postmeta pm ON p.ID = pm.post_id WHERE p.post_type = "'.$post_type_name.'" AND p.post_status = "publish" AND tr.term_taxonomy_id= "'.$terms[$v]->term_id.'" ORDER BY p.post_date DESC', OBJECT);?>
                    
					<?php 
				   echo $region_detail[$v];
                if (!empty($vendor_data)) {
                    foreach ($vendor_data as $row) {?>
                    <div class="vendor come-out">
                  <?php $post_thumbnail_id = get_post_thumbnail_id( $row->ID ); 
						if($post_thumbnail_id != ''){
						$vendor_qr = $wpdb->get_results('SELECT * FROM wp_posts WHERE ID="'.$post_thumbnail_id.'"', OBJECT);
						?>
						<?php } ?>
						
						<?php 
							$html_post = get_option('post_html');
							$html_post = str_replace("[image]",'<img height="205" width="360" src="'. $vendor_qr[0]->guid.'" title="'.$vendor_qr[0]->post_title.'" alt="'. $vendor_qr[0]->post_title.'"/>',$html_post);
							$html_post = str_replace("[title]",$row->post_title,$html_post);
							$html_post = str_replace("[content]",$row->post_content,$html_post);
							$post_ex =  $row->post_excerpt;
							if($post_ex == ''){
								$post_ex = substr(strip_tags($row->post_content), 0, 152).' ...';
							}
							$html_post = str_replace("[excerpt]",$post_ex,$html_post);
							$html_post = str_replace("[post_link]",get_permalink( $row->ID ),$html_post);
							$author_id = get_post_field ('post_author', $row->ID);
							$html_post = str_replace("[author_name]",get_the_author_meta( 'display_name' , $author_id ),$html_post);
							$html_post = str_replace("[author_link]",get_author_posts_url( $author_id, get_the_author_meta( 'user_nicename' ) ),$html_post);
							$html_post = str_replace("[post_date]",date_format(date_create($row->post_date), 'Y-m-d'),$html_post);
							$html_post = str_replace("[author_image]",get_avatar($author_id ),$html_post);
							
							$pcf_break = explode("[post_custom_",$html_post);
							if(sizeof($pcf_break) > 0){
								foreach ($pcf_break as $pcfs){
									$pcf_breaks = explode('_field]',$pcfs);
									$pfield_array = get_field($pcf_breaks[0],$row->ID);
									if(is_array($pfield_array)){
										$pfield_array = '<img src="'.$pfield_array['url'].'" alt="'.$pfield_array['alt'].'" />';
									}
									$html_post = str_replace('[post_custom_'.$pcf_breaks[0].'_field]',$pfield_array,$html_post);
								
								}
							}
							
							
							echo $html_post;
						?>
                    </div>
                    <?php
                    }//foreach
                }//if
				 		
                ?>
                </div>

                <?php 
	       
	  		}//for ?>

            </div><!-- region-vendors -->

			</div>
			
		</div>
		
			
	<?php
		//return '<ul>'.$post_taxonomy_filters.'</ul>'; 		
}
add_shortcode('post_taxonomy_filters', 'post_taxonomy_filters');



#-----------------------------------------------------------------#
# End Show Filter Data Shortcode
#-----------------------------------------------------------------#

#-----------------------------------------------------------------#
# Start Ajax Filter
#-----------------------------------------------------------------#
add_action( 'wp_ajax_post_detail', 'post_detail_callback' );
add_action( 'wp_ajax_nopriv_post_detail', 'post_detail_callback' );
function post_detail_callback() { ?>
	
	<div class="region-vendor active" data-region="<?php echo $_POST['data_type'];?>">

                    <?php 
					//echo '<br>--------<br>Testing<br>-----------<br>';
					global $wpdb;
					if(esc_attr( get_option('post_taxonomy_post_type') ) != ''){
						$post_type_name = esc_attr( get_option('post_taxonomy_post_type') );
					}else{
						$post_type_name = 'post';
					}
					
					$vendor_data = $wpdb->get_results('SELECT DISTINCT(p.ID) , p.post_title,p.post_excerpt,p.post_content,p.post_date, p.guid FROM wp_posts p INNER JOIN wp_term_relationships tr ON p.ID = tr.object_id INNER JOIN wp_postmeta pm ON p.ID = pm.post_id WHERE p.post_type = "'.$post_type_name.'" AND p.post_status = "publish" AND tr.term_taxonomy_id= "'.$_POST['term_id'].'" ORDER BY p.post_date DESC', OBJECT);?>
                    
					<?php 
					//print_r($vendor_data);
					
				   //echo $region_detail[$v];
                if (!empty($vendor_data)) {
                    foreach ($vendor_data as $row) {?>
                    <div class="vendor come-out">
                  <?php $post_thumbnail_id = get_post_thumbnail_id( $row->ID ); 
						if($post_thumbnail_id != ''){
						$vendor_qr = $wpdb->get_results('SELECT * FROM wp_posts WHERE ID="'.$post_thumbnail_id.'"', OBJECT);
						?>
						<?php } ?>
						
						<?php 
							$html_post = get_option('post_html');
							$html_post = str_replace("[image]",'<img height="205" width="360" src="'. $vendor_qr[0]->guid.'" title="'.$vendor_qr[0]->post_title.'" alt="'. $vendor_qr[0]->post_title.'"/>',$html_post);
							$html_post = str_replace("[title]",$row->post_title,$html_post);
							$html_post = str_replace("[content]",$row->post_content,$html_post);
							$post_ex =  $row->post_excerpt;
							if($post_ex == ''){
								$post_ex = substr(strip_tags($row->post_content), 0, 152).' ...';
							}
							$html_post = str_replace("[excerpt]",$post_ex,$html_post);
							$html_post = str_replace("[post_link]",get_permalink( $row->ID ),$html_post);
							$author_id = get_post_field ('post_author', $row->ID);
							$html_post = str_replace("[author_name]",get_the_author_meta( 'display_name' , $author_id ),$html_post);
							$html_post = str_replace("[author_link]",get_author_posts_url( $author_id, get_the_author_meta( 'user_nicename' ) ),$html_post);
							$html_post = str_replace("[post_date]",date_format(date_create($row->post_date), 'Y-m-d'),$html_post);
							$html_post = str_replace("[author_image]",get_avatar($author_id ),$html_post);
							
							$pcf_break = explode("[post_custom_",$html_post);
							if(sizeof($pcf_break) > 0){
								foreach ($pcf_break as $pcfs){
									$pcf_breaks = explode('_field]',$pcfs);
									$pfield_array = get_field($pcf_breaks[0],$row->ID);
									if(is_array($pfield_array)){
										$pfield_array = '<img src="'.$pfield_array['url'].'" alt="'.$pfield_array['alt'].'" />';
									}
									$html_post = str_replace('[post_custom_'.$pcf_breaks[0].'_field]',$pfield_array,$html_post);
								
								}
							}
							
							
							echo $html_post;
						?>
                    </div>
                    <?php
                    }//foreach
                }//if
				 		
                ?>
								
                </div>
								
								
				<?php
				
				
				exit;
	
}
#-----------------------------------------------------------------#
# End Ajax Filter
#-----------------------------------------------------------------#


#-----------------------------------------------------------------#
# Start add Script and css File
#-----------------------------------------------------------------#
function load_custom_wp_admin_style($hook) {
        // Load only on ?page=post-taxonomy-settings
        if($hook != 'toplevel_page_post-taxonomy-settings') {
                return;
        }
        wp_enqueue_style( 'ptf_css', plugins_url('/css/ptf-style.css', __FILE__) );
		 wp_enqueue_script( 'ptf_script',plugins_url('/js/ptf-script.js', __FILE__)  );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

function plugin_script(){
	wp_enqueue_script( 'filter-script', plugins_url('/js/filter-script.js', __FILE__), array ( 'jquery' ), 1, true);
	wp_enqueue_style( 'theme-css', plugins_url('/css/theme-css.css', __FILE__) );
}
add_action( 'wp_enqueue_scripts', 'plugin_script' );
#-----------------------------------------------------------------#
# End add Script and css File
#-----------------------------------------------------------------#



