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
		1  => 'Событие обновлено. <a href="' . esc_url( get_permalink( $post_ID ) ) . '">Просмотр</a>',
		2  => 'Параметр обновлён.',
		3  => 'Параметр удалён.',
		4  => 'Событие обновлена',
		5  => isset( $_GET['revision'] ) ? sprintf( 'Событие восстановлено из редакции: %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => 'Событие опубликовано на сайте. <a href="' . esc_url( get_permalink( $post_ID ) ) . '">Просмотр</a>',
		7  => 'Событие сохранено.',
		8  => 'Отправлено на проверку. <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">Просмотр</a>',
		9  => 'Запланировано на публикацию: <strong>' . date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) . '</strong>.'
		      . '<a target="_blank" href="' . esc_url( get_permalink( $post_ID ) ) . '">Просмотр</a>',
		10 => 'Черновик обновлён. <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">Просмотр</a>',
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
		'content' => '<h3>Обзор</h3><p>На этом экране доступны все имеющиеся события. Вы можете настроить отображение этого экрана согласно своим предпочтениям.</p>'
	);

	// Добавляем вкладку
	$screen->add_help_tab( $args );

	$content = <<< TEXT
<h3>Доступные действия с &laquo;событиями&raquo;</h3>
<p>При наведении курсора на строку в списке записей появятся ссылки, позволяющие управлять записью. Вы можете выполнить следующие действия:</p>
<ul>
	<li><strong>«Редактировать»</strong> — открывает экран редактирования этой записи. Туда также можно попасть, нажав на заголовок записи.</li>
	<li><strong>«Свойства»</strong> — предоставляет быстрый доступ к метаданным записи, позволяя изменять настройки записи прямо на этом экране.</li>
	<li><strong>«Удалить»</strong> — убирает запись из этого списка и помещает её в корзину, откуда можно удалить её навсегда.</li>
	<li><strong>«Просмотреть»</strong> — показывает, как будет выглядеть ваш черновик после публикации. «Перейти» — открывает запись на внешней части сайта. Какая из этих двух ссылок будет отображаться, зависит от статуса записи.</li>
</ul>
TEXT;

	// Массив параметров для второй вкладки
	$args = array(
		'id'      => 'tab_2',
		'title'   => 'Доступные действия',
		'content' => $content,
	);

	// Добавляем вторую вкладку
	$screen->add_help_tab( $args );

}