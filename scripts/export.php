<?php

if ( PHP_SAPI !== 'cli' ) {
    exit( 1 );
}
$root = dirname( __DIR__ );
$_SERVER['HTTP_HOST'] = '127.0.0.1:8079';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';
define( 'MEDVED_STATIC_EXPORT', true );
require $root . '/.local/wordpress/wp-load.php';

$post = get_post( (int) get_option( 'page_on_front' ) );
if ( ! $post || $post->post_status !== 'publish' ) {
    throw new RuntimeException( 'Опубликуйте главную страницу в WordPress.' );
}
setup_postdata( $post );
ob_start();
include get_template_directory() . '/front-page.php';
$html = ob_get_clean();

$output = $root . '/docs';
wp_mkdir_p( $output );
$theme_assets = get_template_directory() . '/assets';
$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $theme_assets, FilesystemIterator::SKIP_DOTS ) );
foreach ( $iterator as $asset ) {
    if ( ! $asset->isFile() ) {
        continue;
    }
    $path = $output . '/assets' . substr( $asset->getPathname(), strlen( $theme_assets ) );
    wp_mkdir_p( dirname( $path ) );
    if ( ! copy( $asset->getPathname(), $path ) ) {
        throw new RuntimeException( 'Не удалось скопировать ' . $asset->getFilename() );
    }
}

$theme_url = get_template_directory_uri();
$asset_urls = array( $theme_url . '/assets/', wp_parse_url( $theme_url, PHP_URL_PATH ) . '/assets/' );
$html = str_replace( $asset_urls, './assets/', $html );
$uploads = wp_get_upload_dir();
$upload_root = realpath( $uploads['basedir'] );
$upload_url = preg_quote( $uploads['baseurl'], '~' );
$upload_path = preg_quote( wp_parse_url( $uploads['baseurl'], PHP_URL_PATH ), '~' );
$upload_pattern = '~(?<=["\'\s])(?:' . $upload_url . '|' . $upload_path . ')' . '/([^\s"\'<>?,]+)~';
$html = preg_replace_callback( $upload_pattern, function ( $match ) use ( $upload_root, $output ) {
    $relative = rawurldecode( $match[1] );
    $file = $upload_root ? realpath( $upload_root . DIRECTORY_SEPARATOR . $relative ) : false;
    if ( ! $file || ! is_file( $file ) || ! str_starts_with( $file, $upload_root . DIRECTORY_SEPARATOR ) ) {
        throw new RuntimeException( 'Не найдено медиа WordPress: ' . $relative );
    }
    $path = $output . '/media/' . $relative;
    wp_mkdir_p( dirname( $path ) );
    if ( ! copy( $file, $path ) ) {
        throw new RuntimeException( 'Не удалось экспортировать медиа: ' . $relative );
    }
    return './media/' . $match[1];
}, $html );
if ( ! is_string( $html ) || file_put_contents( $output . '/index.html', rtrim( $html ) . PHP_EOL ) === false ) {
    throw new RuntimeException( 'Не удалось записать статическую страницу.' );
}
file_put_contents( $output . '/.nojekyll', '' );
echo "Export: $output/index.html\n";
