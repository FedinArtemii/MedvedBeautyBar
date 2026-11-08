<?php
$static  = defined( 'MEDVED_STATIC_EXPORT' );
$assets  = medved_assets();
$home    = $static ? '#top' : home_url( '/' );
$name    = get_bloginfo( 'name' );
$vk      = 'https://vk.com/medved_bb';
$desc    = 'Medved Beauty Bar в Челябинске. Маникюр, педикюр, брови и ресницы. Запись в сообществе ВКонтакте.';
$public  = 'https://fedinartemii.github.io/MedvedBeautyBar/';
$arrow   = '<svg class="arrow" aria-hidden="true" viewBox="0 0 20 20" fill="none"><path d="M5 15 15 5M6 5h9v9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?><!doctype html>
<html lang="ru" data-type="place" data-radius="sharp" data-buttons="solid" data-motion>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?php echo esc_attr( $desc ); ?>">
<meta name="theme-color" content="#f086b6">
<meta name="page-type" content="place">
<meta name="site-kind" content="other">
<meta name="page-kind" content="place">
<meta property="og:type" content="website">
<meta property="og:locale" content="ru_RU">
<meta property="og:title" content="<?php echo esc_attr( $name ); ?> — студия красоты">
<meta property="og:description" content="<?php echo esc_attr( $desc ); ?>">
<meta property="og:image" content="<?php echo esc_url( ( $static ? $public . 'assets' : $assets ) . '/img/share.jpg' ); ?>">
<?php if ( $static ) : ?>
<title><?php echo esc_html( $name ); ?> — студия красоты в Челябинске</title>
<link rel="canonical" href="<?php echo esc_url( $public ); ?>">
<meta property="og:url" content="<?php echo esc_url( $public ); ?>">
<?php else : wp_head(); endif; ?>
<link rel="icon" href="<?php echo esc_url( $assets . '/img/logo.webp' ); ?>" type="image/webp">
<link rel="stylesheet" href="<?php echo esc_url( $assets . '/fonts/fonts.css' ); ?>">
<link rel="stylesheet" href="<?php echo esc_url( $assets . '/site.css' ); ?>">
<link rel="stylesheet" href="<?php echo esc_url( $assets . '/motion/motion.css' ); ?>">
<script>document.documentElement.classList.add('m-enter');setTimeout(()=>document.documentElement.classList.add('m-enter-done'),700)</script>
<script type="module" src="<?php echo esc_url( $assets . '/motion/motion.js' ); ?>"></script>
<script type="module" src="<?php echo esc_url( $assets . '/site.js' ); ?>"></script>
</head>
<body <?php body_class(); ?>>
<?php if ( ! $static ) { wp_body_open(); } ?>
<a class="skip-link" href="#main">К содержанию</a>
<header class="site-head nav" id="top">
    <div class="head-row wrap">
        <a class="brand" href="<?php echo esc_url( $home ); ?>" aria-label="<?php echo esc_attr( $name ); ?>">
            <span class="brand-mark"><img src="<?php echo esc_url( $assets . '/img/logo-160.webp' ); ?>" srcset="<?php echo esc_attr( $assets . '/img/logo-160.webp 160w, ' . $assets . '/img/logo-320.webp 320w' ); ?>" sizes="153px" width="153" height="153" alt=""></span>
            <span><strong>MEDVED</strong>BEAUTY BAR</span>
        </a>
        <nav class="desktop-nav" aria-label="Разделы страницы">
            <a class="m-link m-link--bare" href="#services">Услуги</a>
            <a class="m-link m-link--bare" href="#visit">Как добраться</a>
        </nav>
        <a class="head-action m-link m-link--bare" href="<?php echo esc_url( $vk ); ?>">Мы ВКонтакте <?php echo $arrow; ?></a>
        <details class="mobile-menu">
            <summary><span>Меню</span></summary>
            <nav aria-label="Разделы страницы">
                <a class="m-link m-link--bare" href="#services">Услуги</a>
                <a class="m-link m-link--bare" href="#visit">Как добраться</a>
                <a class="m-link m-link--bare" href="<?php echo esc_url( $vk ); ?>">Мы ВКонтакте <?php echo $arrow; ?></a>
            </nav>
        </details>
    </div>
</header>
<main id="main" tabindex="-1">
<?php
if ( $static ) {
    the_content();
} else {
    while ( have_posts() ) {
        the_post();
        the_content();
    }
}
?>
</main>
<footer class="site-footer">
    <div class="wrap footer-row">
        <a class="m-link m-link--bare" href="#top"><?php echo esc_html( $name ); ?></a>
        <p>Иллюстративные фото <a href="https://www.pexels.com/photo/pink-flower-on-hand-with-polished-nails-20758448/">Pexels</a></p>
    </div>
</footer>
<?php if ( ! $static ) { wp_footer(); } ?>
</body>
</html>
