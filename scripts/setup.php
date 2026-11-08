<?php

if ( PHP_SAPI !== 'cli' ) {
    exit( 1 );
}

$local = dirname( __DIR__ ) . '/.local';
$wordpress = $local . '/wordpress';
$site_url = 'http://127.0.0.1:8079';

if ( ! file_exists( $wordpress . '/wp-config.php' ) ) {
    $config = "<?php\n";
    $constants = array(
        'DB_NAME'              => 'medved_local',
        'DB_USER'              => 'local',
        'DB_PASSWORD'          => '',
        'DB_HOST'              => 'localhost',
        'DB_CHARSET'           => 'utf8mb4',
        'DB_COLLATE'           => '',
        'WP_HOME'              => $site_url,
        'WP_SITEURL'           => $site_url,
        'WP_ENVIRONMENT_TYPE'  => 'local',
        'DISABLE_WP_CRON'      => true,
        'WP_HTTP_BLOCK_EXTERNAL' => true,
        'WP_DEBUG'             => false,
    );
    foreach ( array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ) as $key ) {
        $constants[ $key ] = bin2hex( random_bytes( 32 ) );
    }
    foreach ( $constants as $key => $value ) {
        $config .= 'define( ' . var_export( $key, true ) . ', ' . var_export( $value, true ) . " );\n";
    }
    $config .= '$table_prefix = "wp_";' . "\nrequire_once __DIR__ . '/wp-settings.php';\n";
    file_put_contents( $wordpress . '/wp-config.php', $config );
}

$_SERVER['HTTP_HOST'] = '127.0.0.1:8079';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';
define( 'WP_INSTALLING', true );
require $wordpress . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

if ( ! is_blog_installed() ) {
    $password = wp_generate_password( 24, false );
    add_filter( 'pre_wp_mail', '__return_true' );
    wp_install( 'Medved Beauty Bar', 'medved-admin', 'admin@localhost.test', false, '', $password, 'ru_RU' );
    file_put_contents( $local . '/admin.txt', "URL: $site_url/wp-admin/\nUsername: medved-admin\nPassword: $password\n" );
}

$theme = 'medved-beauty-bar';
if ( get_stylesheet() !== $theme ) {
    switch_theme( $theme );
}

if ( ! get_option( 'page_on_front' ) ) {
    $source = file_get_contents( get_theme_root() . '/' . $theme . '/landing.blocks.html' );
    $content = str_replace( '__MEDVED_ASSETS__', get_theme_root_uri() . '/' . $theme . '/assets', $source );
    $page_id = wp_insert_post( wp_slash( array(
        'post_title'   => 'Главная',
        'post_name'    => 'home',
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_author'  => get_user_by( 'login', 'medved-admin' )->ID,
    ) ), true );
    if ( is_wp_error( $page_id ) ) {
        fwrite( STDERR, $page_id->get_error_message() . PHP_EOL );
        exit( 1 );
    }
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $page_id );
    update_option( 'timezone_string', 'Asia/Yekaterinburg' );
}
echo "WordPress: $site_url/\n";
