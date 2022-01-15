<?php
include './includes/header.inc.php';
if (!isset($_SESSION['userid'])) header("Location: ./auth.php");
$userid = $_SESSION['userid'];
if(!isset($_GET['id'])) header("Location: ./forbidden.php");
$problemData = getProblemData($_GET['id']);
?>
<main class="container">
    <h3 class="text-center mt-2">Edit Problem</h3>
    <div class="container flex pt-5" style="padding-left:25%;">
        <form action="#" method="post" style="width:70%" class="text-center" name="problemform" id='problemform'>
            <input type="text" hidden name="prob_id" value="<?=$_GET['id'];?>">
            <div class="input-group mb-3">
                <span class="input-group-text" id="oj-name-label">Judge Name</span>
                <select class="form-select-sm" id="oj-name" required name="oj">
                    <option value="">Select OJ</option>
                    <?= getOjSelect($_GET['id']); ?>
                </select>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="name-label">Problem Name</span>
                <input type="text" class="form-control" id="name" name='name' required value="<?=$problemData['name'];?>">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="">Problem Link</span>
                <input type="text" class="form-control" id="link" required name="link" value="<?=$problemData['url'];?>">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="">Problem Tags</span>
                <select class="form-select tagselect" reequired name="tags[]" multiple="multiple" id='tags' style="width: 50%;">
                    <?= getTagSelect($_GET['id']); ?>
                </select>
            </div>
            <div class="sub-button">
                <button class="btn btn-info" type="submit" name="probedit" id="probedit">Submit</button>
            </div>
        </form>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="problem_toast toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="problem_toast-header text-center">
                <img src="./assets/logo.png" class="rounded me-2" alt="..." height="20px">
                <strong class="text-center">CPDOCS</strong>
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
        $('#probedit').on("click", function() {
            document.getElementById("probedit").disabled = true;
            var form = document.getElementById('problemform');
            var formData = new FormData(form);
            formData.append('action','edit');
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
                    $('.problem_toast-body').html('<p class="text-center mt-2">'+response+'</p>');
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
                        document.getElementById("probedit").disabled = false;
                    }

                },
            });
        })
    });
</script>