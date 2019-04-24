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
  // META_BOX
    $meta_box_slug = implode("_", ["metabox", THEME_HASH, $NAME]);

    $meta_box=[
      "post_type"  => $NAME,
      "slug"       => $meta_box_slug,
      "label"      => $options["label"],
      "context"    => "normal",
      "priority"   => "core",
      "nonce_key"  => $meta_box_slug,
      "nonce_name" => implode("_", ["nonce", "metabox", $NAME]),
    ];
    $meta_box["callback"]= function($post_id, $post, $updated){
      $selling_price= $_POST["item_selling_price"];
      $status= $_POST["item_status"];

      if($selling_price === '')$selling_price= "0";

      if(isset($selling_price))update_post_meta($post_id, "item_selling_price", $selling_price);
      if(isset($status))update_post_meta($post_id, "item_status", $status);
    };
    $options["register_meta_box_cb"] = custom_meta_box($meta_box)["register"];
    add_action("save_post_{$NAME}", custom_meta_box($meta_box)["callback"], 10, 3);


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
