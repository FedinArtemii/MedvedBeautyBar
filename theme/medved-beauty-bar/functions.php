<?php

function medved_assets() {
    return get_template_directory_uri() . '/assets';
}

add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'editor-styles' );
    add_editor_style( array( 'assets/fonts/fonts.css', 'assets/site.css' ) );
} );

add_filter( 'render_block_core/group', function ( $html, $block ) {
    if ( ( $block['attrs']['tagName'] ?? '' ) !== 'section' ) {
        return $html;
    }
    $tags = new WP_HTML_Tag_Processor( $html );
    if ( ! $tags->next_tag( 'SECTION' ) ) {
        return $html;
    }
    if ( $tags->has_class( 'hero' ) ) {
        $tags->set_attribute( 'data-enter', '' );
    } elseif ( in_array( $tags->get_attribute( 'id' ), array( 'services', 'visit' ), true ) ) {
        $tags->set_attribute( 'tabindex', '-1' );
    }
    return $tags->get_updated_html();
}, 10, 2 );

add_filter( 'render_block_core/image', function ( $html, $block ) {
    $classes = explode( ' ', $block['attrs']['className'] ?? '' );
    $hero    = in_array( 'hero-photo', $classes, true );
    $photo   = $hero || in_array( 'service-photo', $classes, true ) || in_array( 'visit-photo', $classes, true );
    if ( ! $photo ) {
        return $html;
    }
    $tags = new WP_HTML_Tag_Processor( $html );
    if ( $tags->next_tag( 'IMG' ) ) {
        $tags->set_attribute( 'decoding', 'async' );
        $tags->set_attribute( 'loading', $hero ? 'eager' : 'lazy' );
        if ( $hero ) {
            $tags->set_attribute( 'fetchpriority', 'high' );
        }
        $src = $tags->get_attribute( 'src' );
        $dimensions = array(
            'hero.webp'  => array( 1500, 1000 ),
            'nails.webp' => array( 567, 850 ),
            'brows.webp' => array( 705, 850 ),
            'visit.webp'  => array( 1400, 933 ),
        );
        $theme_image_path = wp_parse_url( medved_assets(), PHP_URL_PATH ) . '/img/';
        if ( is_string( $src ) && str_starts_with( wp_parse_url( $src, PHP_URL_PATH ) ?? '', $theme_image_path ) && isset( $dimensions[ basename( $src ) ] ) ) {
            list( $width, $height ) = $dimensions[ basename( $src ) ];
            $tags->set_attribute( 'width', $width );
            $tags->set_attribute( 'height', $height );
        }
    }
    return $tags->get_updated_html();
}, 10, 2 );


add_filter( 'render_block_core/button', function ( $html, $block ) {
    if ( ! in_array( 'booking-button', explode( ' ', $block['attrs']['className'] ?? '' ), true ) ) {
        return $html;
    }
    $tags = new WP_HTML_Tag_Processor( $html );
    if ( $tags->next_tag( 'A' ) ) {
        $tags->add_class( 'btn btn--primary btn--arrow' );
    }
    return $tags->get_updated_html();
}, 10, 2 );
