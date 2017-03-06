<?php
defined('ABSPATH') or die("Cannot access pages directly.");


	 function categoryList($term){



		 global $wpdb;

		 $out = array();

		 $a = $wpdb->get_results($wpdb->prepare("SELECT t.name,t.slug,t.term_group,x.term_taxonomy_id,x.term_id,x.taxonomy,x.description,x.parent,x.count FROM {$wpdb->prefix}term_taxonomy x LEFT JOIN {$wpdb->prefix}terms t ON (t.term_id = x.term_id) WHERE x.taxonomy=%s;",$term));


		 $output = array('' => 'Choose Option');


		 foreach ($a as $b) {


			 $output[$b->term_id] = $b->name;

		 }

		 return $output;




	 }


	function postList($post_type="post", $cargs=""){


	$query_args[] = array();

	$query_args['post_type'] = $post_type;
	$query_args['order'] = 'ASC';
	$query_args['orderby'] = 'title';


	if(!empty($cargs)){


		$args = array_merge($query_args,$cargs);


	}else{

		$args = $query_args;
	}


	$query = new WP_Query( $args );


	$output = array('' => 'Choose Option');





	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();


			$output[get_the_ID()] = get_the_title();



		}

		wp_reset_postdata();

	}






	return $output;



}


