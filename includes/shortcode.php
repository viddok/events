<?php
// Создание шорткода
function events_shortcode( $atts ) {
	$param = shortcode_atts(
		array(
			'status'         => 'open',
			'posts_per_page' => 5,
		),
		$atts
	);

	$date   = strtotime( date( 'd-m-Y' ) );
	$q_args = array(
		'post_type'      => 'events',
		'posts_per_page' => $param['posts_per_page'],
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'   => 'status',
				'value' => $param['status'],
			),
			array(
				'key'     => 'date',
				'value'   => $date,
				'compare' => '>='
			),
		),
		'meta_key'       => 'date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC'
	);

	$q    = new WP_Query( $q_args );
	$html = '';
	if ( $q->have_posts() ):
		$html = '<ul>';
		while ( $q->have_posts() ):
			$q->the_post();
			$html .= '<li>' . $q->post->post_title . ' - ' . date( 'd-m-Y', get_post_meta( get_the_ID(), 'date', true ) ) . '</li>';
		endwhile;
		$html .= '</ul>';
	endif;
	wp_reset_postdata();

	return $html;
}