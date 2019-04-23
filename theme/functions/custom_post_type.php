<?
// // enable to use rewrite tag 'top' for querying top posts
//   add_action("init", function(){
//     add_rewrite_tag("%top%", "(true|false)");
//   }, 1);
//   // and,,, provides a conditional helper for custom post types;
//   function is_top_archive($post_type= null){
//     $isTop= get_query_var("top") === "true";
//     if(empty($post_type))return $isTop;
//     return $isTop AND is_post_type_archive($post_type);
//   }
//

function custom_meta_box($opts){
  $renderer= function($post, $args)use($opts){
    extract($opts);
    wp_nonce_field($nonce_key, $nonce_name);
    Phug::displayFile(__DIR__ . "/${post_type}.pug", [
      "post"=>$post,
      "args"=>$args,
    ],["expressionLanguage"=>"php"]);
  };
  $register= function()use($opts, $renderer){
    extract($opts);
    add_meta_box($slug, $label, $renderer, get_current_screen(), $context, $priority);
  };
  $callback= function($post_id, $post, $updated)use($opts){
    extract($opts);
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if(!current_user_can("edit_post", $post->ID)) return;
    if(!is_user_logged_in()) return;
    if(!wp_verify_nonce($_POST[$nonce_name], $nonce_key))return;
    $callback($post_id, $post, $updated);
  };
  return compact("register", "callback");
}


// custom permalink helper for custom_post_type
  // usage: __custom_permalink_for("news", "news/%s")<---- %s is replaced with $post->ID
  function __custom_permalink_for($post_type, $format){
    add_filter("post_type_link", function($url, $post) use($post_type, $format){
      if($post->post_type !== $post_type) return $url;
      $unpublished= ['draft', 'pending', 'auto-draft', 'future'];
      if(in_array(get_post_status($post->ID), $unpublished))
        return home_url(add_query_arg([
          "post_type"=>$post_type,
          "p"=>$post->ID
        ], ""));
      return home_url(sprintf($format, $post->ID));
    }, 10 ,2);
  }

// custom template loading helper for custom_post_type
  function __custom_archive_template($post_type, $cb){
    add_filter("__custom_archive_template", function($template)use($post_type, $cb){
      if(get_post_type() !== $post_type)return $template;
      return $cb();
    });
  }
