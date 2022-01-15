<?php
include '../includes/functions.inc.php';
// var_dump($_POST);
$orderType = $_POST['sort_order'][0];
$Order = 'date_created';
$offset = $_POST['offset'];
if ($orderType == 'date_n_2_o') {
    $Order .= ' DESC';
} else if($orderType == 'upvote')
    $Order = 'votes DESC';
$types = '';
$authorName = $_POST['authorName'];
$keywords = $_POST['keywords'];
foreach ($_POST['blog_type'] as $t) {
    if ($t == 'editorial') $types .= '0';
    else $types .= '1';
    $types .= ',';
}
$types = substr_replace($types, "", -1);
$sql = "SELECT b.*, COALESCE(SUM(v.type),0) as votes
    FROM blog as b LEFT JOIN vote as v ON b.id = v.blog_id 
    WHERE 
        b.type IN ($types) 
        AND b.user_id 
            IN(SELECT id FROM user WHERE username LIKE '%$authorName%')
        AND title LIKE '%$keywords%'
        AND b.status = 1
    GROUP BY b.id
    ORDER BY $Order
    LIMIT $offset,20
    ";
$res = myquery($sql);
?>
<?php
while ($row = mysqli_fetch_assoc($res)) {
    $id = $row['id'];
    $heading = $row['title'];
    $body = keep15Line($row['body']);
    $type = $row['type'];
    $user_id = $row['user_id'];
    $userData = getUserData($user_id);
    $userName = $userData['username'];
    $date_created = $row['date_created'];
    $date_created = date("F j, Y", strtotime($date_created));
?>
    <div class="one-post border m-2 p-3">
        <div class="">
            <h3><?= $heading; ?></h3>
            <div class="d-flex justify-content-between">
                <p>By <a href="./user.php?id=<?= $user_id; ?>"><?= $userName; ?></a></p>
                <p><?= $date_created; ?></p>
            </div>
        </div>
        <?= $body; ?>
        <a href="./blog.php?id=<?= $id; ?>" class="btn btn-outline-secondary">Read More</a>
        <div class="d-flex justify-content-between mt-2">
            <div class="d-flex justify-content-center">
                <div class="left">
                    <button class="btn btn-outline-success"><i class='bx bxs-up-arrow' id="up-vote"></i></button>
                </div>
                <div class="vote-count mx-1 ">
                    <p class="p-2"><?=getVote($id);?></p>
                </div>
                <div class="left">
                    <button class="btn btn-outline-danger"><i class='bx bxs-down-arrow' id="up-vote"></i></button>
                </div>

            </div>
            <div class="user-bottom d-flex align-item-center justify-content-between gap-5">
                <div class="user-box d-flex">
                    <div class="icon-user">
                        <i class='bx bx-user'></i>
                    </div>
                    <div class="user-name ps-1">
                        <p><a href="./user.php?id=<?= $user_id; ?>"><?= $userName; ?></a></p>
                    </div>
                </div>
                <div class="comment-box d-flex">
                    <div class="icon-user">
                        <i class='bx bx-chat'></i>
                    </div>
                    <div class="user-name ps-1">
                        <p>200</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>