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

  // META_BOXES
    $renderer= function($name){
      e= function($post, $args){Phug::displayFile(__DIR__ . "/item/selling_price.pug", ["post"=>$post, "args"=>$args],["expressionLanguage"=>"php"]);};

    add_meta_box(, $selling_price, get_current_screen(), "side", "core");
    $boxes=[
      ["name"=> "selling_price", "label"=>"販売価格"],
    ]
    $options["register_meta_box_cb"]= function()use($NAME, $boxes){
      foreach($boxes as $item){
        add_meta_box(join("_", [$NAME, $item["name"]]),$item["label"], $renderer($item["name"]), get_current_screen(), "side", "core");
      }
    };

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
