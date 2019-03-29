<?php
$user_id = Auth::user()->id;
$buyer_id = $post_company[0]->user_id;
$job_id = $job_preview[0]['id'];
if (isset($_SESSION['page']) && !empty($_SESSION['page'])) {
    $page = $_SESSION['page'];
} else {
    $page = 0;
}
$backlink = "http://" . $_SERVER['SERVER_NAME'] . $page;
jobViewUpdate($job_preview[0]['id'],$user_id);

?>

