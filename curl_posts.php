<?php
include 'classes.php';
$url = "https://jsonplaceholder.typicode.com/posts";
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);
$result=curl_exec($ch);
curl_close($ch);
$posts = json_decode($result, true);
foreach($posts as $post){
    $userId = $post['userId'];
    $title = $post['title'];
    $body = $post['body'];
    $post = new Post();
    $check = $post->checkDuplicate($title);
    if($check==0){
        $post->create($userId, $title, $body);
    }
}

echo "Posts Added. Go to <a href='index.php'>Homepage</a>";