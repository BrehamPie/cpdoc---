<?php
include './includes/header.inc.php';
if (!isset($_SESSION['userid'])) header("Location: ./auth.php");
$userid = $_SESSION['userid'];
?>
<main class="container">
    <h3 class="text-center mt-2">Add a New Problem</h3>
    <div class="container flex pt-5" style="padding-left:25%;">
        <form action="#" method="post" style="width:70%" class="text-center" name="problemform" id='problemform'>
            <div class="input-group mb-3">
                <span class="input-group-text" id="oj-name-label">Judge Name</span>
                <select class="form-select-sm" id="oj-name" required name="oj">
                    <option value="">Select OJ</option>
                    <?= getOjSelect(); ?>
                </select>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="name-label">Problem Name</span>
                <input type="text" class="form-control" id="name" name='name' required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="">Problem Link</span>
                <input type="text" class="form-control" id="link" required name="link">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="">Problem Tags</span>
                <select class="form-select tagselect" reequired name="tags[]" multiple="multiple" id='tags' style="width: 50%;">
                    <?= getTagSelect(); ?>
                </select>
            </div>
            <div class="sub-button">
                <button class="btn btn-info" type="submit" name="probsubmit" id="probsubmit">Submit</button>
            </div>
        </form>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="problem_toast toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="problem_toast-header">
                <img src="./assets/logo.png" class="rounded me-2" alt="..." height="20px">
                <strong class="me-auto">CPDOCS</strong>
                <small>Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="problem_toast-body">
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('.tagselect').select2({
            placeholder: "Select Problem Tags",
            allowClear: true
        });
        $('#probsubmit').on("click", function() {
            document.getElementById("probsubmit").disabled = true;
            var form = document.getElementById('problemform');
            var formData = new FormData(form);
            formData.append('action','insert');
            event.preventDefault();
            // Check file selected or not
            $.ajax({
                url: './ajax/save_prob.ajax.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    $('.problem_toast-body').html(response);
                    var toastElList = [].slice.call(document.querySelectorAll('.problem_toast'))
                    var toastList = toastElList.map(function(toastEl) {
                        return new bootstrap.Toast(toastEl)
                    })
                    toastList.forEach(toast => toast.show());
                    if (response.charAt(response.length - 1) == 'y') {
                        setTimeout(function() {
                            window.location.href = './user.php?id=<?= $userid; ?>&problems';
                        }, 2000);
                    }else{
                        document.getElementById("probsubmit").disabled = false;
                    }

                },
            });
        })
    });
</script>