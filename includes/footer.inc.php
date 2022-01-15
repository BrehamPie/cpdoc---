<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="general_toast" class="general_toast toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="general_toast-header">
            <img src="./assets/logo.png" class="rounded me-2" alt="..." height="20px">
            <strong class="me-auto">CPDOCS</strong>
            <small>Now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="general_toast-body">
        </div>
    </div>
</div>
<footer>
    <hr>
    <p class="text-center">Created By- BrehamPie</p>
</footer>
</body>
<script>
    function showTags() {
        $(".show-tag").toggle();
    }

    function pagination(totalPages, page, webpage) {
        const ulTag = document.getElementById("pagin");
        let liTag = '';
        let beforePages = page - 1;
        let afterPages = page + 1;
        let activeLi;
        if (page > 1) {
            liTag += `<li class="btn prev" onclick="location.href='./${webpage}.php?page=${beforePages}'"><span><i class="fas fa-angle-left"></i>Prev</span></li>`;
        }
        if (page > 2) {
            liTag += `<li class="numb "  onclick="location.href='./${webpage}.php?page=1'"><span>1</span></li>`;
            if (page > 3) {
                liTag += `<li class="dot"><span>...</span></li>`;
            }
        }

        for (let pageLength = Math.max(1, beforePages); pageLength <= Math.min(totalPages, afterPages); pageLength++) {
            if (pageLength == page) {
                activeLi = "active";
            }
            liTag += `<li class="numb ${activeLi}" onclick="location.href='./${webpage}.php?page=${pageLength}'"><span>${pageLength}</span></li>`;
            activeLi = "";
        }

        if (page < totalPages - 1) {
            if (page < totalPages - 2) {
                liTag += `<li class="dot"><span>...</span></li>`;
            }
            liTag += `<li class="numb" onclick="location.href='./${webpage}.php?page=${totalPages}'"><span>${totalPages}</span></li>`;
        }
        if (page < totalPages) {
            liTag += `<li class="btn next" onclick="location.href='./${webpage}.php?page=${afterPages}'"><span>Next<i class="fas fa-angle-right"></i></span></li>`;
        }
        ulTag.innerHTML = liTag;
    }

    function sendVote(blogid, voteType, userid) {
        var formData = new FormData();
        formData.append('action', voteType);
        formData.append('blogid', blogid);
        formData.append('userid', userid);
        console.log(formData);
        // Check file selected or not
        $.ajax({
            url: './ajax/givevote.ajax.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                $('.general_toast-body').html(response);
                var toastElList = [].slice.call(document.querySelectorAll('.general_toast'))
                var toastList = toastElList.map(function(toastEl) {
                    return new bootstrap.Toast(toastEl)
                })
                toastList.forEach(toast => toast.show())
            }
        });

    }
</script>

</html>