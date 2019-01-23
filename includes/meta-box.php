<?php
// подключаем функцию активации мета блока (my_extra_fields)
add_action( 'add_meta_boxes', 'add_events_settings_meta_box', 1 );

function add_events_settings_meta_box() {
	add_meta_box( 'event_fields', __( 'Настройки события', 'lang' ), 'events_settings_meta_box', 'events', 'normal', 'high' );
}

// код блока
function events_settings_meta_box( $post ){
	?>
	<p><?php _e( 'Статус события', 'lang' ); ?>: <?php $mark_v = get_post_meta($post->ID, 'status', 1); ?>
        <?php $mark_v = ( '' == $mark_v ) ? 'open' : $mark_v; ?>
		<label><input type="radio" name="event[status]" value="open" <?php checked( $mark_v, 'open' ); ?> /> <?php _e( 'Открытое', 'lang' ); ?></label>
		<label><input type="radio" name="event[status]" value="closed" <?php checked( $mark_v, 'closed' ); ?> /> <?php _e( 'По приглашению', 'lang' ); ?></label>
	</p>
	<p>
        <label>
			<?php
            $date = get_post_meta( $post->ID, 'date', 1 );
			if ( '-1' == $date ) {
                $text = '<span style="color: #8B4545; font-weight: bold">*' . __( 'Вы забыли ввести дату', 'lang') . '</span>';
				update_post_meta( $post->ID, 'date', '' );
			} elseif ( ! empty($date) && ( strtotime( date( 'Y-m-d' ) ) > $date ) ) {
				$text = '<span style="color: #8B4545; font-weight: bold">*' . __( 'Указанная дата уже прошла', 'lang') . '</span>';
			} else {
				$text = '<span>*' . __( 'Дата события', 'lang' ) . '</span>';
			}
			echo $text;
			?>: <br/>
            <?php if ( '' === get_post_meta( $post->ID, 'date', 1 ) ) {
                $date_val = '';
            } else {
                $date_val = date( 'd-m-Y', get_post_meta( $post->ID, 'date', 1 ) );
            } ?>
            <input type="text" name="event[date]" value="<?php echo $date_val; ?>" style="width:50%" placeholder="18-04-2018"/>
        </label>
	</p>

	<input type="hidden" name="event_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
}

// включаем обновление полей при сохранении
add_action( 'save_post', 'event_fields_update', 0 );

## Сохраняем данные, при сохранении поста
function event_fields_update( $post_id ) {
	// базовая проверка
	if (
		empty( $_POST['event'] )
		|| ! wp_verify_nonce( $_POST['event_fields_nonce'], __FILE__ )
		|| wp_is_post_autosave( $post_id )
		|| wp_is_post_revision( $post_id )
	) {
		return false;
	}

	// Все ОК! Теперь, нужно сохранить/удалить данные
	$_POST['event'] = array_map( 'sanitize_text_field', $_POST['event'] ); // чистим все данные от пробелов по краям
	foreach( $_POST['event'] as $key => $value ){
        if ('date' === $key) {
	        if( '' === $value) {
		        $value = -1;
	        } else {
		        $value = strtotime( $value );
	        }
        }

		update_post_meta( $post_id, $key, $value ); // add_post_meta() работает автоматически
	}

	return $post_id;
}