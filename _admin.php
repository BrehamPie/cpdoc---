<?php
include './includes/header.inc.php';
if (!isset($_SESSION['userid']) || $_SESSION['role'] == 1) header("Location: ./forbidden.php");
?>
<main>
    <div class="container mt-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="blog-tab" data-bs-toggle="tab" data-bs-target="#blog" type="button" role="tab" aria-controls="blog" aria-selected="false">Blogs</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="problem-tab" data-bs-toggle="tab" data-bs-target="#problem" type="button" role="tab" aria-controls="problem" aria-selected="false">Problems</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="blog" role="tabpanel" aria-labelledby="blog-tab">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Title</th>
                            <th scope="col">Status</th>
                            <th> </th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $blogs = myquery("SELECT * FROM blog ORDER BY date_updated DESC");
                        $cnt = 0;
                        while ($row = mysqli_fetch_assoc($blogs)) {
                            $userData = getUserData($row['user_id']);
                            $username = $userData['username'];
                            $title = $row['title'];
                            $status = 'Pending';
                            $rowClass = 'table-warning';
                            if ($row['status'] == -1) {
                                $status = 'Rejected';
                                $rowClass = 'table-danger';
                            }
                            if ($row['status'] == 1) {
                                $status = 'Accepted';
                                $rowClass = 'table-success';
                            }
                            $cnt++;
                        ?>
                            <tr class="text-center <?= $rowClass; ?>">
                                <th scope="row"><?= $cnt; ?></th>
                                <td><?= $username; ?></td>
                                <td><a href="./blog.php?id=<?= $row['id']; ?>" target="_blank"> <?= $title; ?></a></td>
                                <td><?= $status; ?></td>
                                <td><button class="btn btn-success" onclick="process('blog',<?= $row['id']; ?>,1)">Accept</button></td>
                                <td><button class="btn btn-danger" onclick="process('blog',<?= $row['id']; ?>,-1)">Reject</button></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade <?= $probs; ?>" id="problem" role="tabpanel" aria-labelledby="problem-tab">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Source</th>
                            <th scope="col">Problem Name</th>
                            <th scope="col">Tags</th>
                            <th scope="col">Status</th>
                            <th scope="col"> </th>
                            <th scope="col"> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = myquery("SELECT * FROM problem");
                        $cnt = 0;
                        while ($row = mysqli_fetch_assoc($res)) {
                            $cnt++;
                            $name = $row['name'];
                            $url = $row['url'];
                            $source = getOjLink($row['oj_id']);
                            $tags = getTagListWithLink($row['id']);
                            $userData = getUserData($row['user_id']);
                            $username = $userData['username'];
                            $status = 'Pending';
                            $rowClass = 'table-warning';
                            if ($row['status'] == -1) {
                                $status = 'Rejected';
                                $rowClass = 'table-danger';
                            }
                            if ($row['status'] == 1) {
                                $status = 'Accepted';
                                $rowClass = 'table-success';
                            }
                        ?>
                            <tr class="<?= $rowClass; ?>">
                                <th scope="row"><?= $cnt; ?></th>
                                <th scope="row"><?= $username; ?></th>
                                <td><?= $source; ?></td>
                                <td><a href="<?= $url; ?>"><?= $name; ?></a></td>
                                <td><?= $tags; ?></td>
                                <td><?= $status; ?></td>
                                <td><button class="btn btn-success" onclick="process('problem',<?= $row['id']; ?>,1)">Accept</button></td>
                                <td><button class="btn btn-danger" onclick="process('problem',<?= $row['id']; ?>,-1)">Reject</button></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function process(action,id, value) {
            var formData = new FormData();
            formData.append('action', action);
            formData.append('id', id);
            formData.append('value', value);
            // Check file selected or not
            $.ajax({
                url: './ajax/admin.ajax.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    location.reload();
                },
            });
        }
    </script>
    <?php
    include './includes/footer.inc.php';
    ?>
</main>