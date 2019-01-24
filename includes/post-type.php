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
		'public'              => true,
		'show_in_nav_menus'   => false, // зависит от public
		'show_in_rest'        => false, // добавить в REST API. C WP 4.7
		'menu_position'       => 4,
		'menu_icon'           => 'dashicons-groups',
		'hierarchical'        => false, // Для обновления надо пересохранить настройки ЧПУ в Админке
		'supports'            => array('title'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => array(),
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	) );
}

// Настройка текстов уведомления для ного типа записи
add_filter( 'post_updated_messages', 'true_post_type_messages' );
function true_post_type_messages( $messages ) {
	global $post, $post_ID;

	$messages['events'] = array( // events - название созданного нами типа записей
		0  => '', // Данный индекс не используется.
		1  => __( 'Событие обновлено', 'lang' ) . '. <a href="' . esc_url( get_permalink( $post_ID ) ) . '">' . __( 'Просмотр', 'lang' ) . '</a>',
		2  => __( 'Параметр обновлён.', 'lang' ),
		3  => __( 'Параметр удалён.', 'lang' ),
		4  => __( 'Событие обновлена', 'lang' ),
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Событие восстановлено из редакции:', 'lang' ) . ' %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Событие опубликовано на сайте', 'lang' ) . '. <a href="' . esc_url( get_permalink( $post_ID ) ) . '">' . __( 'Просмотр', 'lang' ) . '</a>',
		7  => __( 'Событие сохранено.', 'lang' ),
		8  => __( 'Отправлено на проверку', 'lang' ) . '. <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">' . __( 'Просмотр', 'lang' ) . '</a>',
		9  => __( 'Запланировано на публикацию:', 'lang' ) . ' <strong>' . date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) . '</strong>.'
		      . '<a target="_blank" href="' . esc_url( get_permalink( $post_ID ) ) . '">' . __( 'Просмотр', 'lang' ) . '</a>',
		10 => __( 'Черновик обновлён', 'lang' ) . '. <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">' . __( 'Просмотр', 'lang' ) . '</a>',
	);

	return $messages;
}

// Вкладка помощь
add_action( 'admin_head', 'true_post_type_help_tab' );
function true_post_type_help_tab() {

	$screen = get_current_screen();

	// Прекращаем выполнение функции, если находимся на страницах других типов постов
	if ( 'events' != $screen->post_type ) {
		return;
	}

	// Массив параметров для первой вкладки
	$args = array(
		'id'      => 'tab_1',
		'title'   => 'Обзор',
		'content' => '<h3>' . __( 'Обзор', 'lang' ) . '</h3><p>' . __( 'На этом экране доступны все имеющиеся события. 
		Вы можете настроить отображение этого экрана согласно своим предпочтениям', 'lang' ) . '.</p>'
	);

	// Добавляем вкладку
	$screen->add_help_tab( $args );

	$content = '<h3>' . __( 'Доступные действия с «событиями»', 'lang' ) . '</h3>'
	           . '<ul>'
	           . '<li><strong>' . __( '«Редактировать»', 'lang' ) . '</strong> — ' . __( 'открывает экран редактирования 
	           этой записи. Туда также можно попасть, нажав на заголовок записи.', 'lang' ) . '</li>'
	           . '<li><strong>' . __( '«Свойства»', 'lang' ) . '</strong> — ' . __( 'предоставляет быстрый доступ к 
	           метаданным записи, позволяя изменять настройки записи прямо на этом экране.', 'lang' ) . '</li>'
	           . '<li><strong>' . __( '«Удалить»', 'lang' ) . '</strong> — ' . __( 'убирает запись из этого списка и 
	           помещает её в корзину, откуда можно удалить её навсегда.', 'lang' ) . '</li>'
	           . '<li><strong>' . __( '«Просмотреть»', 'lang' ) . '</strong> — ' . __( 'показывает, как будет выглядеть 
	           ваш черновик после публикации. «Перейти» — открывает запись на внешней части сайта. Какая из этих двух 
	           ссылок будет отображаться, зависит от статуса записи.', 'lang' ) . '</li>'
	           . '</ul>';

	// Массив параметров для второй вкладки
	$args = array(
		'id'      => 'tab_2',
		'title'   => __( 'Доступные действия', 'lang' ),
		'content' => $content,
	);

	// Добавляем вторую вкладку
	$screen->add_help_tab( $args );

}

// хук для регистрации таксономии
add_action('init', 'create_taxonomy');
function create_taxonomy(){
	// список параметров:
	register_taxonomy('events_category', 'events', array(
		//'label'                 => '', // определяется параметром $labels->name
		'labels'                => array(
			'name'              => __( 'Рубрики событий', 'lang' ),
			'singular_name'     => __( 'Рубрика событий', 'lang' ),
			'search_items'      => __( 'Искать рубрики', 'lang' ),
			'all_items'         => __( 'Все рубрики', 'lang' ),
			'view_item '        => __( 'Просмотреть рубрику', 'lang' ),
			'parent_item'       => __( 'Родительскае рубрика', 'lang' ),
			'parent_item_colon' => __( 'Родительская рубрика', 'lang' ) . ':',
			'edit_item'         => __( 'Редактировать рубрику', 'lang' ),
			'update_item'       => __( 'Обновить рубрику', 'lang' ),
			'add_new_item'      => __( 'Добавить новую рубрику', 'lang' ),
			'new_item_name'     => __( 'Имя новой рубрики', 'lang' ),
			'menu_name'         => __( 'Рубрики событий', 'lang' ),
		),
		//'description'           => 'Некоторое описание', // описание таксономии
		'public'                => true,
		//'publicly_queryable'    => null, // равен аргументу public
		'show_in_nav_menus'     => true, // равен аргументу public
		'show_ui'               => true, // равен аргументу public
		'show_in_menu'          => true, // равен аргументу show_ui
		'show_tagcloud'         => true, // равен аргументу show_ui
		'show_in_rest'          => false, // добавить в REST API
		//'rest_base'             => null, // Ярлык в REST API. По умолчанию, название таксономии.
		'hierarchical'          => true,
		'update_count_callback' => '',
		'rewrite'               => array(
			'slug'          => '',
			'hierarchical'  => true,
		),
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // callback функция. Отвечает за html код метабокса (с версии 3.8):
		// post_categories_meta_box или post_tags_meta_box. Если указать false, то метабокс будет отключен вообще
		'show_admin_column'     => true, // Позволить или нет авто-создание колонки таксономии в таблице
		// ассоциированноготипа записи. (с версии 3.5)
		'_builtin'              => false,
		'show_in_quick_edit'    => null, // по умолчанию значение show_ui
	) );
}