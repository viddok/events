<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 23.01.19
 * Time: 8:23
 */

namespace includes\Classes;


class Upcoming_Events extends \WP_Widget {

	/*
	 * Создание виджета
	 */
	function __construct() {
		parent::__construct(
			'upcoming-events',
			__( 'Ближайшие собития', 'lang' ),
			array( 'description' => __( 'Вывод ближайших событий согласно указанного указанных в найтках статус и кол-ва.', 'lang' ) )
		);
	}

	/*
	 * фронтэнд виджета
	 */
	public function widget( $args, $instance ) {
		$title          = apply_filters( 'widget_title', $instance['title'] ); // к заголовку применяем фильтр (необязательно)
		$event_status   = $instance['event_status'];
		$posts_per_page = $instance['posts_per_page'];

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$date   = strtotime( date( 'd-m-Y' ) );
		$q_args = array(
			'post_type'      => 'events',
			'posts_per_page' => $posts_per_page,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'   => 'status',
					'value' => $event_status,
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

		$q = new \WP_Query( $q_args );
		if ( $q->have_posts() ):
			?>
            <ul><?php
			while ( $q->have_posts() ): $q->the_post();
				?>
                <li>
				<?php the_title() ?> - <?php echo date( 'd-m-Y', get_post_meta( get_the_ID(), 'date', true ) ); ?>
                </li><?php
			endwhile;
			?></ul><?php
		endif;
		wp_reset_postdata();

		echo $args['after_widget'];
	}

	/*
	 * бэкэнд виджета
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = '';
		}
		if ( isset( $instance['posts_per_page'] ) ) {
			$posts_per_page = $instance['posts_per_page'];
		} else {
			$posts_per_page = 5;
		}
		if ( isset( $instance['event_status'] ) ) {
			$event_status = $instance['event_status'];
		} else {
			$event_status = 'open';
		}
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Заголовок', 'lang' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'event_status' ); ?>"><?php _e( 'Статус события', 'lang' ); ?></label><br/>
            <input id="<?php echo $this->get_field_id( 'event_status' ); ?>"
                   name="<?php echo $this->get_field_name( 'event_status' ); ?>" type="radio"
                   value="open" <?php checked( $event_status, 'open' ); ?> /> <?php _e( 'Открытое', 'lang' ); ?>
            <input id="<?php echo $this->get_field_id( 'event_status' ); ?>"
                   name="<?php echo $this->get_field_name( 'event_status' ); ?>" type="radio"
                   value="closed" <?php checked( $event_status, 'closed' ); ?> /> <?php _e( 'По приглашению', 'lang' ); ?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e( 'Количество постов:', 'lang' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"
                   name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="text"
                   value="<?php echo ( $posts_per_page ) ? esc_attr( $posts_per_page ) : '5'; ?>" size="3"/>
        </p>
		<?php
	}

	/*
	 * сохранение настроек виджета
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                   = array();
		$instance['title']          = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['posts_per_page'] = ( is_numeric( $new_instance['posts_per_page'] ) ) ? $new_instance['posts_per_page'] : '5'; // по умолчанию выводятся 5 постов
		$instance['event_status']   = ( 'open' == $new_instance['event_status'] ) ? 'open' : 'closed';

		return $instance;
	}
}