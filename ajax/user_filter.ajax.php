<?php
include '../includes/functions.inc.php';
session_start();
//var_dump($_POST);
$orderType = $_POST['sort_order'][0];
$Order = 'join_date';
$offset = $_POST['offset'];
if ($orderType == 'date_n_2_o') {
    $Order .= ' DESC';
} else if ($orderType == 'contribution')
    $Order = 'cntbr DESC';
else if ($orderType == 'username')
    $Order = 'username';
$country = $_POST['country'];
$name = $_POST['name'];
$sql = "SELECT c_table.*,COALESCE((c_table.s_cnt*1+c_table.b_cnt*5+c3.p_cnt*2),0) as cntbr FROM (SELECT t.*,COALESCE(t.solve_cnt,0) as s_cnt,COALESCE(c2.b_cnt,0) as b_cnt  FROM ( SELECT u.*,COALESCE(s_cnt,0)as solve_cnt FROM user as u LEFT JOIN ( SELECT user_id,COUNT(user_id) as s_cnt FROM solved WHERE status = 1) c1 ON u.id = c1.user_id) t LEFT JOIN ( SELECT user_id,COUNT(user_id) as b_cnt FROM blog WHERE status = 1 GROUP BY user_id) c2 ON t.id = c2.user_id) c_table LEFT JOIN (SELECT user_id,COUNT(user_id) as p_cnt  FROM problem WHERE status = 1 GROUP BY user_id)c3 ON c_table.id = c3.user_id
WHERE c_table.username LIKE '%$name%'
AND c_table.country IN (SELECT id FROM country WHERE nicename LIKE '%$country%')
ORDER BY $Order
LIMIT $offset,50";
//echo $sql;
$res = myquery($sql);
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Country</th>
            <th scope="col">Contribution</th>
            <th scope="col">Joined On</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cell = 0;
        while ($row = mysqli_fetch_assoc($res)) {
            $cell++;
            $id = $row['id'];
            $userName = $row['username'];
            $date_created = $row['join_date'];
            $date_created = date("F j, Y", strtotime($date_created));
            $country = getCountry($row['country']);
            $contrib = $row['cntbr'];

        ?>
            <tr>
                <th scope="row"><?= $cell; ?></th>
                <td><a href="./user.php?id=<?= $id; ?>"><?= $userName; ?></a></td>
                <td><?= $country; ?></td>
                <td><?= $contrib; ?></td>
                <td><?= $date_created; ?></td>

            </tr>
        <?php
        }
        ?>
    </tbody>
</table>