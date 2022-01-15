<?php
include '../includes/functions.inc.php';
session_start();
//var_dump($_POST);
$orderType = $_POST['sort_order'][0];
$Order = 'date_created';
$offset = $_POST['offset'];
if ($orderType == 'date_n_2_o') {
    $Order .= ' DESC';
} else if ($orderType == 'solve')
    $Order = 'solve_cnt DESC';
$ojList = $_POST['ojName'];
$ojs = '';
foreach ($ojList as $oj) {
    $ojs .= $oj . ',';
}
$ojs = substr_replace($ojs, '', -1);
$tags = $_POST['tags'];
$name = $_POST['name'];

$sql = "SELECT p.*, COUNT(s.problem_id) as solve_cnt
        FROM problem as p LEFT JOIN solved as s ON p.id = s.problem_id 
        WHERE oj_id IN ($ojs) AND p.name LIKE '%$name%' 
        AND p.id IN(SELECT problem_id FROM problem_tag WHERE tag_id IN(select id FROM tag WHERE name LIKE '%$tags%'))
        GROUP BY p.id
        ORDER BY $Order
        LIMIT $offset,50
        ";
$res = myquery($sql);
?>
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