<?
add_action("after_setup_theme", function(){

  $GLOBALS['content_width']= 640;

  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'title-tag' );

  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 1568, 9999 );

  add_theme_support("html5", ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
});
