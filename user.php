<?php
include './includes/header.inc.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $userData = getUserData($id);
    $userName = $userData['username'];
    $fullname = $userData['fullname'];
    $about = $userData['about'];
    $organization = $userData['organization'];
    $country = $userData['country'];
    $countryName = getCountry($country);
    $joinDate = $userData['join_date'];
    $date = date("j F, Y", strtotime($joinDate));
    $contribution = getContribution($id);
    $blogCount = getBlogCount($id);
    $problemCount = getProblemCount($id);
    $solveCount = getSolveCount($id);
    $img = $userData['img'];
} else {
    header("Location: ./forbidden.php");
}
$dashboard = '';
$blogshow = '';
$probs = '';
$setting = '';
if (isset($_GET['blogs'])) {
    $blogshow = 'show active';
} else if (isset($_GET['problems'])) {
    $probs = 'show active';
} else if (isset($_GET['setting'])) {
    $setting = 'show active';
} else $dashboard = 'show active';
?>
<div class="container mt-3">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $dashboard; ?>" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Dashboard</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $blogshow; ?>" id="blog-tab" data-bs-toggle="tab" data-bs-target="#blog" type="button" role="tab" aria-controls="blog" aria-selected="false">Blogs</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $probs; ?>" id="problem-tab" data-bs-toggle="tab" data-bs-target="#problem" type="button" role="tab" aria-controls="problem" aria-selected="false">Problems</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $setting; ?>" id="setting-tab" data-bs-toggle="tab" data-bs-target="#setting" type="button" role="tab" aria-controls="setting" aria-selected="false">Settings</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade <?= $dashboard; ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-3">
                    <div class="user-pic mt-2">
                        <img src="./assets/img/users/<?= $img; ?>" alt="" height="250px" class="p-3 border">
                    </div>
                </div>
                <div class="col-9 d-flex justify-content-between">
                    <div class="info">
                        <div class="user-name">
                            <h4 class="mb-0"><?= $userName; ?></h4>
                            <p class="mb-0"><?= $fullname; ?></p class="mb-0">
                            <small class="mt-0">From <?= $organization; ?>, <?= $countryName; ?> </small>
                        </div>
                        <div class="contribution mt-5">
                            <ul style="list-style: none; margin: 0; padding: 0;">
                                <li> Contribution: <?= $contribution; ?></li>
                                <li> Blogs Posted: <?= $blogCount; ?></li>
                                <li> Problems Added: <?= $problemCount; ?> </li>
                                <li> Problems Solved: <?= $solveCount; ?></li>
                            </ul>
                        </div>
                        <div class="join mt-3">
                            <p>Joined On: <?= $date; ?></p>
                        </div>
                    </div>
                    <div class="about text-center pt-5 pe-5">
                        "<?= $about; ?>"
                    </div>
                </div>

            </div>
            <div class="recent-activity row mt-5">
                <div class="col-6">
                    <h5 class="text-center">Recent Blogs by <?= $userName; ?></h5>
                    <ul class="list-group list-group-flush">
                        <?php
                        $blogs = getBlogsByauthor($id);
                        $cnt = 0;
                        while ($res = mysqli_fetch_assoc($blogs)) {
                            $cnt++;
                            $blogID = $res['id'];
                            $title = $res['title'];
                        ?>
                            <li class="list-group-item"><a href="./blog.php?id=<?= $blogID; ?>"><?= $title; ?></a></li>
                        <?php
                            if ($cnt == 5) break;
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-6">
                    <h5 class="text-center">Recent Problems Added by <?= $userName; ?></h5>
                    <ul class="list-group list-group-flush">
                        <?php
                        $problems = getProblemsByAuthor($id);
                        $cnt = 0;
                        while ($res = mysqli_fetch_assoc($problems)) {
                            $cnt++;
                            $problemID = $res['id'];
                            $title = $res['name'];
                            $url = $res['url'];
                        ?>
                            <li class="list-group-item"><a href="<?= $url; ?>"><?= $title; ?></a></li>
                        <?php
                            if ($cnt == 5) break;
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-pane fade <?= $blogshow; ?>" id="blog" role="tabpanel" aria-labelledby="blog-tab">
            <h4>Blogs posted by <?= $userName; ?></h4>
            <a class="btn btn-outline-secondary" href="./newpost.php">Create New Blog</a>
            <table class="table">
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Upvotes</th>
                            <th scope="col">Downvotes</th>
                            <th scope="col">Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $blogs = getBlogsByauthor($id);
                        $cnt = 0;
                        while ($row = mysqli_fetch_assoc($blogs)) {
                            $title = $row['title'];
                            $upvt = getUpvote($row['id']);
                            $dwnvt = getDownvote($row['id']);
                            $cmnt = getCommentCnt($row['id']);
                            $cnt++;
                        ?>
                            <tr class="text-center">
                                <th scope="row"><?= $cnt; ?></th>
                                <td><?= $title; ?></td>
                                <td><?= $upvt; ?></td>
                                <td><?= $dwnvt; ?></td>
                                <td><?= $cmnt; ?></td>
                                <td><a href="./editpost.php?id=<?=$row['id'];?>" class="btn btn-info">Edit</a></td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                </table>
            </table>
        </div>
        <div class="tab-pane fade <?= $probs; ?>" id="problem" role="tabpanel" aria-labelledby="problem-tab">
            <h4>Problems added by <?= $userName; ?></h4>
            <button class="btn btn-outline-secondary">Add New Problem</button>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Source</th>
                        <th scope="col">Problem Name</th>
                        <th scope="col">Tags</th>
                        <th scope="col">Solved By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = getProblemsByAuthor($id);
                    $cnt = 0;
                    while ($row = mysqli_fetch_assoc($res)) {
                        $cnt++;
                        $name = $row['name'];
                        $url = $row['url'];
                        $solvedBy = getSolveCount($row['id']);
                        $source = getOjLink($row['oj_id']);
                        $tags = getTagListWithLink($row['id']);

                    ?>
                        <tr>
                            <th scope="row"><?= $cnt; ?></th>
                            <td><?= $source; ?></td>
                            <td><a href="<?= $url; ?>"><?= $name; ?></a></td>
                            <td><?= $tags; ?></td>
                            <td><?= $solvedBy; ?></td>
                            <td><a href="./editprob.php?id=<?=$row['id'];?>" class="btn btn-info">Edit Tags</a></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade <?= $setting; ?>" id="setting" role="tabpanel" aria-labelledby="setting-tab">
            <div style="display: flex;align-items: center;margin-bottom: 0;">
                <p>Setting</p>
            </div>
            <hr style="margin-top: 0px;">
            <div class="data">
                <h6>General Info</h6>
                <hr style="color: aqua;">
                <hr>
                <div class="form">
                    <form action="#" class="form-group" method="POST" enctype="multipart/form-data" id='general_data'>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="fullname">Full Name</span>
                            </div>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?= $fullname; ?>">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="country">Select Country</label>
                            </div>
                            <select class="custom-select" id="country" name="country">
                                <option value="0">Choose...</option>
                                <?php
                                $sql = "SELECT * FROM country ORDER BY nicename";
                                $res = myquery($sql);
                                $ret = '';
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $selected = '';
                                    if ($row['id'] == $country) $selected = 'selected';
                                ?>
                                    <option <?= $selected; ?> value="<?= $row['id']; ?>"><?= $row['nicename']; ?></option>';
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="organization">Organization</span>
                            </div>
                            <input type="text" class="form-control" id="organizaton" name="organization">
                        </div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">About Yourself</span>
                    </div>
                    <textarea class="form-control" name="about"><?= $about; ?></textarea>
                </div>
                <div class="input-group col-5 mt-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text me-2">Profile Picture</div>
                    </div>
                    <div class="col-7 mt-1">
                        <input type="file" class="form-control-file" name="filetoupload" accept="image/*">
                    </div>
                </div>
                <div class="input-group col-5 mt-2">
                    <button name="update" type="submit" class="btn btn-info" id='general_update'>Update</button>
                </div>
                </form>
                <p id="info" class='text-center text-success'></p>
            </div>
            <hr>
            <h6>Security</h6>
            <hr>
            <hr>
            <p id="passinfo" class='text-center text-info'></p>
            <form action="user.ajax.php" method="POST" id="pass_form">
                <div class="container">
                    <p id="res" class=text-center></p>
                </div>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="fullname">Old Password</span>
                        </div>
                        <input type="text" class="form-control" id="basic-url" aria-describedby="fullname">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="fullname">New Password</span>
                        </div>
                        <input type="text" class="form-control" id="basic-url" aria-describedby="fullname">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="fullname">Confirm New Password</span>
                        </div>
                        <input type="text" class="form-control" id="basic-url" aria-describedby="fullname">
                    </div>
                    <div class="input-group col-5 mt-2">
                        <button name="security_update" type="submit" class="btn btn-info" id='password_update'>Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="user_toast" class="user_toast toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="user_toast-header">
            <img src="./assets/logo.png" class="rounded me-2" alt="..." height="20px">
            <strong class="me-auto">CPDOCS</strong>
            <small>Now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="user_toast-body">
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#general_update").click(function(event) {
            var form = document.getElementById('general_data');
            var formData = new FormData(form);
            formData.append('action', 'general');
            event.preventDefault();
            // Check file selected or not
            $.ajax({
                url: './ajax/user.ajax.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    $('.user_toast-body').html("Profile Updated.");
                    var toastElList = [].slice.call(document.querySelectorAll('.user_toast'))
                    var toastList = toastElList.map(function(toastEl) {
                        return new bootstrap.Toast(toastEl)
                    })
                    toastList.forEach(toast => toast.show())
                },
            });
        });
    });
</script>
<?php
include './includes/footer.inc.php';
?>