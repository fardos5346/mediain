<?php
include 'classes.php';
$url = "https://jsonplaceholder.typicode.com/users";
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);
$result=curl_exec($ch);
curl_close($ch);
$users = json_decode($result, true);
foreach($users as $user){
    $name = $user['name'];
    $email = $user['email'];
    $user = new User();
    $check = $user->checkDuplicate($email);
    if($check==0){
        $user->create($name, $email);
    }
}

echo "Users added. Go to <a href='index.php'>Homepage</a>";