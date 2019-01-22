<?php
// Регистрация нового post-type Events
add_action( 'init', 'register_post_types' );
function register_post_types(){
	register_post_type('events', array(
		'label'  => null,
		'labels' => array(
			'name'               => __( 'Событие', 'lang' ), // основное название для типа записи
			'singular_name'      => __( 'Событие', 'lang' ), // название для одной записи этого типа
			'add_new'            => __( 'Добавить событие', 'lang' ), // для добавления новой записи
			'add_new_item'       => __( 'Добавление события', 'lang' ), // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => __( 'Редактирование событие', 'lang' ), // для редактирования типа записи
			'new_item'           => __( 'Новое событие', 'lang' ), // текст новой записи
			'view_item'          => __( 'Смотреть событие', 'lang' ), // для просмотра записи этого типа.
			'search_items'       => __( 'Искать событие', 'lang' ), // для поиска по этим типам записи
			'not_found'          => __( 'Не найдено', 'lang' ), // если в результате поиска ничего не было найдено
			'not_found_in_trash' => __( 'Не найдено в корзине', 'lang' ), // если не было найдено в корзине
			'menu_name'          => __( 'События', 'lang' ), // название меню
		),
		//'description'         => '',
		'public'              => true,
		//'publicly_queryable'  => null, // зависит от public
		//'exclude_from_search' => null, // зависит от public
		//'show_ui'             => null, // зависит от public
		//'show_in_menu'        => null, // показывать ли в меню адмнки
		//'show_in_admin_bar'   => null, // по умолчанию значение show_in_menu
		'show_in_nav_menus'   => false, // зависит от public
		'show_in_rest'        => false, // добавить в REST API. C WP 4.7
		//'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => 4,
		'menu_icon'           => 'dashicons-groups',
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false, // !!!!!!!! Ничего не меняется при true либо false
		'supports'            => array('title','custom-fields'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array(),
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	) );
}