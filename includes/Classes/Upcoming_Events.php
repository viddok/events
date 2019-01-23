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
		parent::__construcr(
			'upcoming-events',
			__( 'Ближайшие собития', 'lang' ),
			array( 'description' => __('Вывод ближайших событий согласно указанного указанных в найтках статус и кол-ва.', 'lang') )
		);
	}
}