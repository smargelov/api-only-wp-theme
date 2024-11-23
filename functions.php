<?php
// // Отключение вывода сайта для фронтенда и редирект к API
// add_action('template_redirect', function () {
//     if (!is_admin() && !is_rest_api_request()) {
//         wp_redirect(home_url('/wp-json'), 302);
//         exit;
//     }
// });

function is_rest_api_request() {
    $prefix = rest_get_url_prefix();
    if (strpos($_SERVER['REQUEST_URI'], $prefix) !== false) {
        return true;
    }
    return false;
}

// Отключение XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Отключение лишних скриптов и стилей
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_script('jquery'); // Отключаем jQuery
});

// Отключение REST API ручек по умолчанию
add_filter('rest_endpoints', function ($endpoints) {
    // Удаляем ненужные ручки
    unset($endpoints['/wp/v2/comments']);
    unset($endpoints['/wp/v2/users']);
    return $endpoints;
});

// Настройка CORS для доступа к API
add_action('rest_api_init', function () {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function ($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Credentials: true');
        return $value;
    });
}, 15);

// Отключение доступа к админской панели для всех, кроме администраторов
add_action('init', function () {
    if (is_admin() && !current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)) {
        wp_redirect(home_url());
        exit;
    }
});

/**
 * Carbon fields
 */
add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
/* if vendor directory */
 if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}
require get_template_directory() . '/inc/carbon-fields.php';

// Удаление ненужных виджетов из админ-панели
add_action('wp_dashboard_setup', function () {
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
});

// Настройка простого пустого шаблона
add_action('after_setup_theme', function () {
    add_theme_support('post-thumbnails');
});

// Заглушка для минимальной темы
add_action('wp_head', function () {
    echo '<style>body { display: none; }</style>'; // Скрытие всего контента на frontend
});
