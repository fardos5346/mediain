<?php
include 'classes.php';
$post = new Post();
if(isset($_GET['post_id'])){
    $post_id = $_GET['post_id'];
    echo $post->searchById($post_id);
}elseif(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];
    echo $post->searchByUserId($user_id);
}

echo "<hr>";
echo "Go to <a href='search.php'>Search</a>";
echo "<br>";
echo "Go to <a href='index.php'>Homepage</a>";