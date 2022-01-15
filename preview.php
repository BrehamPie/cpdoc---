<?php
include './includes/header.inc.php';
$process = 'insert';
$blog_id='';
if (isset($_POST['post_submitted'])) {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $type = $_POST['type'];
    $date_created = date("F j, Y");
    $userData = getUserData($_SESSION['userid']);
    $userName = $userData['username'];
} else if (isset($_POST['post_edit'])) {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $type = $_POST['type'];
    $userData = getUserData($_SESSION['userid']);
    $userName = $userData['username'];
    $blog_id = $_POST['post_id'];
    $process = 'update';
    $blogData = getBlogData($blog_id);
    $date_created = date("F j, Y", strtotime($blogData['date_created']));
} else {
    header("Location: ./forbidden.php");
}

?>
<main>
    <div class="container row">
        <div class="col-9 mt-3">
            <div class="top">
                <h4 class="text-center">Post Preview</h4>
            </div>
            <div class="one-post border m-2 p-3">
                <div class="">
                    <h3><?= $title; ?></h3>
                    <div class="d-flex justify-content-between">
                        <p>By <a href="./user.php?id=<?= $user_id; ?>"><?= $userName; ?></a></p>
                        <p><?= $date_created; ?></p>
                    </div>
                </div>
                <div style="white-space: pre-wrap;"><?= $body; ?></div>
            </div>
        </div>
        <div class="col-3 mt-5 flex ">
            <button class="btn btn-secondary" onclick="window.history.go(-1)">Edit Post</button>
            <form action="#" method="POST" class="mt-2" name="post_data" id="post_data">
                <input type="text" hidden name="post_id" value="<?= $blog_id; ?>">
                <input type="text" hidden value="<?= $type; ?>" name="type">
                <textarea name="body" id="body" hidden><?= $body; ?></textarea>
                <textarea name="title" id="title" hidden><?= $title; ?></textarea>
                <button class='btn btn-success' type="submit" name="insert_post" id="insert_post">Submit</button>
            </form>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="./assets/logo.png" class="rounded me-2" alt="..." height="20px">
                <strong class="me-auto">CPDOCS</strong>
                <small>Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#insert_post').on('click', function(event) {
            event.preventDefault();
            document.getElementById("insert_post").disabled = true;
            process('<?= $process; ?>', 'post_data');
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl)
            })
            toastList.forEach(toast => toast.show())
        });

        function process(action, formName) {
            console.log(action, formName);
            var form = document.getElementById(formName);
            var formData = new FormData(form);
            formData.append('action', action);
            // Check file selected or not
            $.ajax({
                url: './ajax/insert_post.ajax.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('.toast-body').html(response);
                    setTimeout(function() {
                        window.location.href = './user.php?id=<?=$_SESSION['userid'];?>&blogs';
                    }, 2000);
                }
            });

        }
    });
</script>