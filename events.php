<?php
/*
Plugin Name: Events
Description: Добавляет на сайт функционал событий. Добавляет виджет с предстоящими собитиями и шорткод для вывода ближайших собтий <strong>[events status="open" posts_per_page="3"]</strong> На любой странице сайта. Параметр <strong>"status"</strong> позволяет фильтровать какие выводить события, принимает значения <strong>open</strong> или <strong>closed</strong>. Параметр <strong>"posts_per_page"</strong> отвечает за кол-во выводимых событий на странице, принимает только целые числа.
Version: 1.0
Author: Евгений Родкин
*/

/*  Copyright 2019  Евгений Родкин  (email: rodkin1987@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Регистрация нового post-type Events
require_once 'includes/post-type.php';

// Регистрация метабокса с произвольными полями
require_once 'includes/meta-box.php';

// Класс виджета
require_once 'includes/Classes/Upcoming_Events.php';

// Подключение шорткода
require_once 'includes/shortcode.php';

// Регистрация виджета
function upcoming_events_load() {
	register_widget( 'includes\Classes\Upcoming_Events' );
}

add_action( 'widgets_init', 'upcoming_events_load' );

// Регистрация шорткода
add_shortcode( 'events', 'events_shortcode' );