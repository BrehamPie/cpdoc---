<?php
include './includes/header.inc.php';
if (!isset($_SESSION['userid'])) {
    header("Location: ./auth.php");
}
?>

<main class="container">
    <h4>New Blog</h4>
    <div class="new-post">
        <form action="./preview.php" method="post">
            <div class="row g-3 align-items-center col-12">
                <div class="col-auto">
                    <label for="title" class="col-form-label">Title</label>
                </div>
                <div class="col-10">
                    <input required type="text" id="title" class="form-control" aria-describedby="title" name="title">
                </div>
            </div>
            <div class="blog-type-radio d-flex">
                <p class="mt-2">Blog Type </p>
                <div class="form-check form-check-inline m-2">
                    <input class="form-check-input" type="radio" name="type" id="editorial" value="editorial" required>
                    <label class="form-check-label" for="editorial">Editorial</label>
                </div>
                <div class="form-check form-check-inline m-2">
                    <input class="form-check-input" type="radio" name="type" id="tutorial" value="tutorial" required>
                    <label class="form-check-label" for="tutorial">Tutorial</label>
                </div>
            </div>
            <div class="mb-3 col-10">
                <label for="body" class="form-label">Body</label>
                <textarea wrap="off" class="form-control" id="body" rows="3" name="body" required></textarea>
            </div>
            <div class="forbut text-center">
                <button type="submit" class="btn btn-outline-secondary" name="post_submitted">Preview</button>
            </div>
        </form>
    </div>
</main>

<script>
    var textarea = document.getElementById("body");
    textarea.oninput = function() {
        textarea.style.height = textarea.scrollHeight+ "px";
    };
</script>