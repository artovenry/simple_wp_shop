<?
add_action("init", function(){
  $NAME= "item";
  $options= [
    "label"        => "ショップアイテム",
    "public"       => true,
    "hierarchical" => false,
    "rewrite"      => false,
    "has_archive"  => true,
    "show_in_rest" => true,
    "supports"     =>
      ["title", "editor", "thumbnail", "excerpt", "revisions"],
  ];


  // META_KEYS AND VALUES
    $meta_key= function($key)use($NAME){
      return "${NAME}_${key}";
    };
    $meta_value= function($post, $slug)use($NAME, $meta_key){
      return get_post_meta($post->ID, $meta_key($slug), true);
    };

  // META_BOXES
    $boxes=[
      [
        "slug"=> "selling_price", "label"=>"販売価格", "callback"=>function($post_id, $post, $updated)use($meta_key){
          $key= $meta_key("selling_price");
          $value= $_POST[$key];
          if(isset($value))update_post_meta($post_id, $key, $value);
        }
      ],
    ];

    $nonce= function($slug)use($NAME){
      $key= "_metabox_${NAME}_${slug}";
      $name="_nonce${key}";
      return ["key" =>$key, "name" => $name];
    };

    $renderer= function($slug)use(
      $nonce, $meta_key, $meta_value
    ){
      return function($post, $args) use(
        $slug, $nonce, $meta_key, $meta_value
      ){
        wp_nonce_field($nonce($slug)["key"], $nonce($slug)["name"]);
        Phug::displayFile(__DIR__ . "/item/${slug}.pug", [
          "post"=>$post,
          "args"=>$args,
          "meta_key"=>$meta_key,
          "meta_value"=>$meta_value
        ],["expressionLanguage"=>"php"]);
      };
    };

    $options["register_meta_box_cb"]= function()use($NAME, $boxes, $renderer){
      foreach($boxes as $item){
        add_meta_box(join("_", [$NAME, $item["slug"]]),$item["label"], $renderer($item["slug"]), get_current_screen(), "side", "core");
      }
    };

    add_action("save_post_${NAME}", function($post_id, $post, $updated)use($boxes, $nonce){
      if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
      if(!current_user_can("edit_post", $post->ID)) return;
      if(!is_user_logged_in()) return;
      foreach($boxes as $item){
        $key= $nonce($item['slug'])['key'];
        $name= $nonce($item['slug'])['name'];
        if(!wp_verify_nonce($_POST[$name], $key))return;
        if(is_callable($item["callback"]))$item["callback"]($post_id, $post, $updated);
      }
    }, 10, 3);

  $routes= [
    // ARCHIVE
    ["shop/?$", "index.php?post_type={$NAME}"],
  ];


  add_action("pre_get_posts", function($query)use($NAME){
    if(is_admin())return;
    if(get_post_type() !== $NAME)return;
    $query->set("posts_per_page", -1);
  });



  register_post_type($NAME, $options);
  foreach($routes as $item)add_rewrite_rule($item[0], $item[1], "top");
  __custom_archive_template($NAME, function(){
    return "{$NAME}-archive";
  });
  __custom_permalink_for($NAME, "{$NAME}/%s");
});
