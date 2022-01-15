<?php
include './includes/header.inc.php';
if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];
    $blogData = getBlogData($blog_id);
    $title = $blogData['title'];
    $body = $blogData['body'];
    $type = $blogData['type'];
    $checkEditorial = 'checked';
    $checkedTutorial = '';
    if ($type == 1) {
        $checkEditorial = '';
        $checkedTutorial = 'checked';
    }
} else {
    header("Location: ./forbidden.php");
}
?>
<main class="container">
    <h4>New Blog</h4>
    <div class="new-post">
        <form action="./preview.php" method="post">
        <input type="text" hidden name="post_id" value="<?=$_GET['id'];?>">
            <div class="row g-3 align-items-center col-12">
                <div class="col-auto">
                    <label for="title" class="col-form-label">Title</label>
                </div>
                <div class="col-10">
                    <input required type="text" id="title" class="form-control" aria-describedby="title" name="title" value='<?= $title; ?>'>
                </div>
            </div>
            <div class="blog-type-radio d-flex">
                <p class="mt-2">Blog Type </p>
                <div class="form-check form-check-inline m-2">
                    <input class="form-check-input" <?= $checkEditorial; ?> type="radio" name="type" id="editorial" value="editorial" required>
                    <label class="form-check-label" for="editorial">Editorial</label>
                </div>
                <div class="form-check form-check-inline m-2">
                    <input class="form-check-input" <?= $checkedTutorial; ?> type="radio" name="type" id="tutorial" value="tutorial" required>
                    <label class="form-check-label" for="tutorial">Tutorial</label>
                </div>
            </div>
            <div class="mb-3 col-10">
                <label for="body" class="form-label">Body</label>
                <textarea wrap="off" class="form-control" id="body" rows="3" name="body" required><?= $body; ?></textarea>
            </div>
            <div class="forbut text-center">
                <button type="submit" class="btn btn-outline-secondary" name="post_edit">Preview</button>
            </div>
        </form>
    </div>
</main>

<script>
    var textarea = document.getElementById("body");
    textarea.oninput = function() {
        textarea.style.height = textarea.scrollHeight + "px";
    };
</script>