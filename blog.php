<?php
include './includes/header.inc.php';
if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];
    $res = getBlogsByID($blog_id);
    $row = mysqli_fetch_assoc($res);
    $heading = $row['title'];
    $body = $row['body'];
    $type = $row['type'];
    $user_id = $row['user_id'];
    $userData = getUserData($user_id);
    $userName = $userData['username'];
    $date_created = $row['date_created'];
    $date_created = date("F j, Y", strtotime($date_created));
    $status = $row['status'];
    $votes = getVote($blog_id);
    if ($status == 0 && $_SESSION['role'] == 1) {
        $heading = "Blog Not approved Yet.";
        $body = '-';
        $userName = '-';
        $date_created = '-';
        $votes = '-';
    }
}
$currentUserName = "anonymous";
if (isset($_SESSION['userid'])) {
    $currentUser = getUserData($_SESSION['userid']);
    $currentUserName = $currentUser['username'];
}
if (isset($_COOKIE[$currentUserName]['blogViewed'])) {
    $blogViewed = json_decode($_COOKIE[$currentUserName]['blogViewed'], true);
    if (key_exists($blog_id, $blogViewed)) {
        $val = $blogViewed[$blog_id];
        foreach ($blogViewed as $key => $value) {
            if ($value > $val) {
                $blogViewed[$key]--;
            }
        }
        $blogViewed[$blog_id] = sizeof($blogViewed);
    } else
        $blogViewed[$blog_id] = sizeof($blogViewed) + 1;
    setcookie($currentUserName . '[blogViewed' . ']', json_encode($blogViewed), time() + 86400 * 7);
} else {
    $blogViewed = [];
    $blogViewed[$blog_id] = 1;
    // var_dump(json_encode($blogViewed));
    setcookie($currentUserName . '[' . 'blogViewed' . ']', json_encode($blogViewed), time() + 86400 * 7);
    setcookie($currentUserName . '[blogViewed' . ']', json_encode($blogViewed), time() + 86400 * 7);
}
?>
<main>
    <div class="container-fluid row ">
        <div class="col-9">
            <div class="one-post border m-2 p-3">
                <div class="">
                    <h3><?= $heading; ?></h3>
                    <div class="d-flex justify-content-between">
                        <p>By <a href="./user.php?id=<?= $user_id; ?>"><?= $userName; ?></a></p>
                        <p><?= $date_created; ?></p>
                    </div>
                </div>
                <?= $body; ?>
                <div class="d-flex justify-content-between mt-2">
                    <div class="d-flex justify-content-center">
                        <div class="left">
                            <button class="btn btn-outline-success"><i class='bx bxs-up-arrow' id="up-vote"></i></button>
                        </div>
                        <div class="vote-count mx-1 ">
                            <p class="p-2"><?= $votes; ?></p>
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
            <div class="comment p-3">
                <form action="" class="mb-5" name="commentform" id="commentform">
                    <div class="mb-3 col-12">
                        <label for="body" class="form-label">Leave a Comment</label>
                        <textarea wrap="off" class="form-control" id="comment" rows="3" name="comment" required></textarea>
                    </div>
                    <div class="buton mb-5 d-flex flex-row-reverse">
                        <button class="btn btn-info" name="comment_submit" id="comment_submit">Submit</button>
                    </div>
                </form>
                <?php
                $sql = "SELECT * FROM comment WHERE blog_id = $blog_id";
                $res = myquery($sql);
               // var_dump(mysqli_num_rows($res));
                while ($row = mysqli_fetch_assoc($res)) {
                    $user_id = $row['user_id'];
                    $userData = getUserData($user_id);
                    $userName = $userData['username'];
                    $body = $row['body'];
                    $img = './assets/img/users/' . $userData['img'];
                    $date_posted = date("F j, Y", strtotime($row['date_added']));
                ?>
                    <div class="one-comment row border">
                        <div class="col-2">
                            <img class="ms-5 mt-3 me-0" src="<?= $img; ?>" alt="" height="40px">
                            <p class="text-center"><?=$userName;?></p>
                            <p><?=$date_posted;?></p>
                        </div>
                        <div class="col-10">
                            <div class="comment-body mt-3">
                                <?=$body;?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
        </div>
        <div class="col-3">
            <div class="author-box border m-2">
                <h4 class="text-center">From Similar Author</h4>
                <ul>
                    <?php
                    $res = getBlogsByauthor($user_id);
                    $count = 0;
                    while ($row = mysqli_fetch_assoc($res)) {
                        if ($count == 5) break;
                        $count++;
                        $id = $row['id'];
                        $heading = $row['title'];
                    ?>
                        <li>
                            <a href="./blog.php?id=<?= $id; ?>"><?= $heading; ?></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="recent-box border m-2">
                <h4 class="text-center">You Recently Viewed</h4>
                <ul>
                    <?php
                    $res = [$blog_id => true];
                    if (isset($_COOKIE[$currentUserName]['blogViewed']))
                        $res = json_decode($_COOKIE[$currentUserName]['blogViewed'], true);
                    $count = 0;
                    arsort($res);
                    foreach ($res as $key => $value) {
                        $sql = "SELECT * FROM blog WHERE id = $key";
                        $row = mysqli_fetch_assoc(myquery($sql));
                        if ($count == 5) break;
                        $count++;
                        $id = $row['id'];
                        $heading = $row['title'];
                    ?>
                        <li>
                            <a href="./blog.php?id=<?= $id; ?>"><?= $heading; ?></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</main>
<?php
include './includes/footer.inc.php';
?>

<script>
    var textarea = document.getElementById("comment");
    textarea.onkeypress = function() {
        textarea.style.height = textarea.scrollHeight + "px";
    };
    $(document).ready(function() {
        $('#comment_submit').on("click", function() {
            var form = document.getElementById('commentform');
            var formData = new FormData(form);
            event.preventDefault();
            formData.append('blog_id', <?= $blog_id; ?>);
            formData.append('user_id', <?= $_SESSION['userid']; ?>);
            // Check file selected or not
            $.ajax({
                url: './ajax/save_comment.ajax.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    form.reset();
                    $('.general_toast-body').html(response);
                    var toastElList = [].slice.call(document.querySelectorAll('.general_toast'))
                    var toastList = toastElList.map(function(toastEl) {
                        return new bootstrap.Toast(toastEl)
                    })
                    toastList.forEach(toast => toast.show());
                },
            });
        })
    });
</script>