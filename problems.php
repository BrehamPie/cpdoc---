<?php
include './includes/header.inc.php';
$limit = 50;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;
?>

<main>
    <div class="container-fluid row ">
        <div class="col-9 problem_container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Status</th>
                        <th scope="col">Source</th>
                        <th scope="col">Problem Name</th>
                        <th scope="col">Added By</th>
                        <th scope="col">Solve Count </th>
                        <th scope="col">Tags</th>
                    </tr>
                </thead>
                <tbody class="problemlist">
                    <?php
                    $res = getProblems($offset, $limit);
                    $cell = 0;
                    while ($row = mysqli_fetch_assoc($res)) {
                        $cell++;
                        $id = $row['id'];
                        $url = $row['url'];
                        $user_id = $row['user_id'];
                        $userData = getUserData($user_id);
                        $userName = $userData['username'];
                        $date_created = $row['date_created'];
                        $date_created = date("F j, Y", strtotime($date_created));
                        $oj = getOjLink($row['oj_id']);
                        $name = $row['name'];
                        $solve_cnt = getSolveCount($id);
                        $tags = getTagListWithLink($id);
                        $checked = '';
                        $userID = '';
                        if (isset($_SESSION['userid'])) {
                            $userID = $_SESSION['userid'];
                            if (hasSolved($userID, $id)) $checked = 'checked';
                        } else $checked = 'disabled';
                    ?>
                        <tr>
                            <th scope="row"><?= $cell; ?></th>
                            <td>
                                <div class="round">
                                    <input type="checkbox" <?= $checked; ?> id="checkbox<?= $id; ?>" onclick="solveToggle(<?= $id; ?>)" />
                                    <label for="checkbox<?= $id; ?>"></label>
                                </div>
                            </td>
                            <td><?= $oj; ?></td>
                            <td><a href="<?= $url; ?>" target="_blank"><?= $name; ?></a> </td>
                            <td><a href="./user.php?id=<?= $user_id; ?>"><?= $userName; ?></a> </td>
                            <td><?= $solve_cnt; ?></td>
                            <td>
                                <div class="d-flex hidden-tag" onclick="showTags(<?= $cell; ?>)">
                                    <i class='bx bxs-right-arrow pt-1 pe-1'></i>
                                    <p>Show Tags</p>
                                </div>
                                <div class="show-tag" style="display: none;">
                                    <small><?= $tags; ?></small>
                                </div>

                            </td>

                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <div class="col-3">
            <div class="create-post text-center mt-2">
                <button class="btn btn-outline-info" onclick="window.location.href = './newprob.php'">Add New Problem</button>
            </div>
            <div class="filter-box ">
                <div class="sort-radio p-2 border mt-2">
                    <h5 class="text-center">Sort Problems By</h5>
                      <input type="radio" id="date_n_2_o" checked name="sort_order" value="date_n_2_o">
                      <label for="date_n_2_o">Date Added(New to Old)</label><br>
                      <input type="radio" id="date_o_2_n" name="sort_order" value="date_o_2_n">
                      <label for="date_o_2_n">Date Added(Old to New)</label><br>
                      <input type="radio" id="upvote" name="sort_order" value="solve">
                      <label for="solve">Solve Count</label><br>
                </div>
                <div class="Type-checkbox p-2 border mt-3">
                    <h5 class="text-center">Online Judges</h5>
                    <?php
                    $sql = "SELECT * FROM oj";
                    $res = myquery($sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $name = $row['name'];
                    ?>
                        <input type="checkbox" id="<?= $name; ?>" checked name="ojname" value="<?= $id; ?>">
                          <label for="<?= $name; ?>"><?= $name; ?></label><br>
                    <?php
                    }
                    ?>
                </div>
                <div class="problem-name-search border mt-3 p-2">
                    <h5 class="text-center">Search By Problem Name</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" name="name">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" name="name-search">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUJJREFUSEu9le0xBEEURc9GQAhEgAzIgAjIABmQARmQgQwQARnYEIiAOlWv1avR3TO7NWOq9sdOd9/z+r6PWbHws1pYnx5gD7gEjoHDCOQdeAHugfWU4FqAuxDvabjnegxSAxjlQRx8BBTynY83uQLO042OepAhoET+FdYU4aGGIK3aCbuEVp8M0POP2GVULfEiJOQt/uy3cpIBJXoT2IxoEOZD2NU8kwHF+ynRD2/h2WouMuA7Tm3aG91zcwEsit1aluey6DWq7g/jX5O8bZlqjyVbHR2tRvsETjq9oOBz+H4L3ExptLInjwrr3BrPo8IBeJEEXTMYg+rmIC+ODTttcc9pzK0mZGxc29GOa4efogr5U1zPLU1nkutVyKZNVXOhC5kDIDRDzoCnEslcgALRzl9xX84JGP0e9D5MW68tfoMfCXxMGRHr0pcAAAAASUVORK5CYII=" />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tag-search border mt-3 p-2">
                    <h5 class="text-center">Search By Tags</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" name="tag">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" name="tag-search">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUJJREFUSEu9le0xBEEURc9GQAhEgAzIgAjIABmQARmQgQwQARnYEIiAOlWv1avR3TO7NWOq9sdOd9/z+r6PWbHws1pYnx5gD7gEjoHDCOQdeAHugfWU4FqAuxDvabjnegxSAxjlQRx8BBTynY83uQLO042OepAhoET+FdYU4aGGIK3aCbuEVp8M0POP2GVULfEiJOQt/uy3cpIBJXoT2IxoEOZD2NU8kwHF+ynRD2/h2WouMuA7Tm3aG91zcwEsit1aluey6DWq7g/jX5O8bZlqjyVbHR2tRvsETjq9oOBz+H4L3ExptLInjwrr3BrPo8IBeJEEXTMYg+rmIC+ODTttcc9pzK0mZGxc29GOa4efogr5U1zPLU1nkutVyKZNVXOhC5kDIDRDzoCnEslcgALRzl9xX84JGP0e9D5MW68tfoMfCXxMGRHr0pcAAAAASUVORK5CYII=" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function showTags(id) {
        console.log(id);
        $('.problemlist tr').eq(id - 1).find('td').eq(5).find('.show-tag').toggle();
    }

    function solveToggle(id) {
        console.log(id);
        var formData = new FormData();
        formData.append('id', id);
        formData.append('uid', <?= $userID; ?>)
        $.ajax({
            url: './ajax/problems.ajax.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
            }
        })
    }
    $(document).ready(function() {
        var name = sessionStorage.getItem("query");
        var tags = '';
        $('input[name="ojname"]').on("change", function(e) {
            filterProblems();
        });
        $('input[name="sort_order"]').on("change", function() {
            filterProblems();
        });
        $('button[name="name-search"]').on("click", function() {
            name = $('input[name="name"]').val();
            filterProblems();
        })
        $('input[name="name"]').on("change", function() {
            name = $('input[name="name"]').val();
        })
        $('button[name="tag-search"]').on("click", function() {
            tags = $('input[name="tag"]').val();
            filterProblems();
        })
        $('input[name="tag"]').on("click", function() {
            tags = $('input[name="tag"]').val();
        })

        function filterProblems() {
            var sort_order = getCheckedData("sort_order");
            console.log(sort_order);
            var ojName = getCheckedData("ojname");
            $.ajax({
                url: './ajax/problem_filter.ajax.php',
                type: 'post',
                data: {
                    sort_order: sort_order,
                    ojName: ojName,
                    name: name,
                    tags: tags,
                    offset: <?= $offset; ?>
                },
                success: function(response) {
                    $('.problem_container').html(response);
                    //console.log(response);
                }
            })

        }

        function getCheckedData(name) {
            var filterData = [];
            $('input[name=' + name + ']:checked').each(function() {
                filterData.push($(this).val());
            })
            return filterData;
        }
        filterProblems();
    })
</script>