<?php
include 'db.inc.php';

// helper function
function myquery($sql)
{
    $connection = $GLOBALS['connection'];
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        print_r(mysqli_error($connection));
    }
    return $result;
}

function getSize($tableName)
{
    $sql = "SELECT COUNT(*) as size FROM $tableName";
    $res = myquery($sql);
    $row = mysqli_fetch_assoc($res);
    return $row['size'];
}
function getLastID($tableName)
{
    $sql = "SELECT id FROM $tableName ORDER BY id DESC";
    $res = myquery($sql);
    $row = mysqli_fetch_assoc($res);
    if ($row == null || $row == '') return 0;
    $id = $row['id'];
    return $id;
}
function keep15Line($string)
{
    $string = explode("\n", $string);
    array_splice($string, 15);
    return implode("\n", $string);
}

// function for users
function getLoginData($username)
{

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $query = myquery($sql);
    $res = mysqli_fetch_assoc($query);
    return $res;
}
function getUserData($userid)
{
    $sql = "SELECT * FROM user WHERE id = $userid";
    $query = myquery($sql);
    $res = mysqli_fetch_assoc($query);
    return $res;
}
function insertUser($username, $email, $password)
{
    $sql = "INSERT INTO user(username,email,password) VALUES('$username','$email','$password')";
    $query = myquery($sql);
}
// function for blogs
function getBlogs($offset, $limit)
{
    $sql = "SELECT * FROM blog ORDER BY date_created DESC LIMIT {$offset},{$limit} ";
    return myquery($sql);
}
function getBlogData($id)
{
    $sql = "SELECT * FROM blog WHERE id = $id";
    $res = myquery($sql);
    return mysqli_fetch_assoc($res);
}
function getBlogsByID($id)
{
    $sql = "SELECT * FROM blog WHERE id = $id";
    return myquery($sql);
}
function getBlogsByauthor($id)
{
    $sql = "SELECT * FROM blog WHERE user_id = $id";
    return myquery($sql);
}
function insertPost($userid, $title, $body, $type)
{
    $sql = "SELECT * FROM blog WHERE title = '$title' AND body = '$body'";
    $res = myquery($sql);
    if ($type == 'editorial') $type = 0;
    else $type = 1;
    if (mysqli_num_rows($res) > 0) return 'Post Already Exist';
    $sql = "INSERT INTO blog(title,body,type,user_id,date_created,date_updated) 
            VALUES('$title','$body',$type,$userid,NOW(),NOW())";
    myquery($sql);
    return 'Your Post Has Been Submitted Successfully.<br>Going Back To Profile.';
}
function editPost($post_id, $title, $body, $type)
{
    $sql = "SELECT * FROM blog WHERE title = '$title' AND body = '$body'";
    $res = myquery($sql);
    if ($type == 'editorial') $type = 0;
    else $type = 1;
    if (mysqli_num_rows($res) > 0) return 'Post Already Exist';
    $sql = "UPDATE blog
            SET
                title = '$title',
                body = '$body',
                type = $type,
                status = 0,
                date_updated = NOW()
            WHERE id = $post_id
                ";
    myquery($sql);
    return 'Your Post Has Been changed Successfully.<br>Going Back To Profile.';
}
function getVote($id)
{
    $sql = "SELECT * FROM vote WHERE type = 1 AND blog_id = $id";
    $res = myquery($sql);
    $sql = "SELECT * FROM vote WHERE type = 0 AND blog_id = $id";
    $res2 = myquery($sql);
    return mysqli_num_rows($res) - mysqli_num_rows($res2);
}
function getUpvote($id)
{
    $sql = "SELECT * FROM vote WHERE type = 1 AND blog_id = $id";
    $res = myquery($sql);
    return mysqli_num_rows($res);
}
function getDownvote($id)
{
    $sql = "SELECT * FROM vote WHERE type = 0 AND blog_id = $id";
    $res = myquery($sql);
    return mysqli_num_rows($res);
}
function getCommentCnt($id)
{
    $sql = "SELECT * FROM comment WHERE blog_id = $id";
    $res = myquery($sql);
    return mysqli_num_rows($res);
}
// function for problems

function getProblems($offset, $limit)
{
    $sql = "SELECT * FROM problem ORDER BY date_created DESC LIMIT {$offset},{$limit} ";
    return myquery($sql);
}
function getProblemsByAuthor($id)
{
    $sql = "SELECT * FROM problem WHERE user_id = $id ORDER BY date_created";
    return myquery($sql);
}

function getProblemTags($id)
{
    $sql = "SELECT tag_id FROM problem_tag WHERE problem_id = $id";
    $query = myquery($sql);
    $array = array();
    while ($res = mysqli_fetch_assoc($query)) {
        array_push($array, $res['tag_id']);
    }
    return $array;
}
function getProblemData($id)
{
    $sql = "SELECT * FROM problem WHERE id = $id";
    $res = myquery($sql);
    return mysqli_fetch_assoc($res);
}
function getTagName($id)
{
    $sql = "SELECT * FROM tag WHERE id = $id";
    $query = myquery($sql);
    $res = mysqli_fetch_assoc($query);
    $name = $res['name'];
    return $name;
}
function getTagListWithLink($id)
{
    $tags = getProblemTags($id);
    $tag = '';
    foreach ($tags as $tid) {
        $tagname = getTagName($tid);
        $tag .=  '<a href="./problems.php?tag=' . $tid . '">' . $tagname . '</a>';
        $tag .= ',';
    }
    $tag = substr($tag, 0, -1);
    return $tag;
}
function getOjLink($id)
{
    $sql = "SELECT * FROM oj WHERE id = $id";
    $res = myquery($sql);
    $row = mysqli_fetch_assoc($res);
    $ret = '<a href =' . $row['address'] . '>' . $row['name'] . '</a>';
    return $ret;
}
function getOjSelect($id = 0)
{
    $sql = "SELECT * FROM oj ORDER BY name";
    $res = myquery($sql);
    $ret = '';
    $selected = '';
    if ($id > 0) {
        $probData = getProblemData($id);
        $selected = $probData['oj_id'];
    }
    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['id'] == $selected) {
            $ret .= '<option value="' . $row['id'] . '" selected>' . $row['name'] . '</option>';
        } else $ret .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
    return $ret;
}
function getCountrySelect($id)
{
    $sql = "SELECT * FROM country ORDER BY nicename";
    $res = myquery($sql);
    $ret = '';
    while ($row = mysqli_fetch_assoc($res)) {
        $selected = '';
        if ($row['id'] == $id) $selected = 'selected';
        echo $selected;
        $ret .= '<option' . $selected . ' value="' . $row['id'] . '">' . $row['nicename'] . '</option>';
    }
    return $ret;
}
function getTagSelect($id = 0)
{
    $sql = "SELECT * FROM tag ORDER BY name";
    $res = myquery($sql);
    $ret = '';
    $tags = [];
    if ($id > 0) {
        $tags = getProblemTags($id);
    }
    while ($row = mysqli_fetch_assoc($res)) {
        if (in_array($row['id'], $tags)) {
            $ret .= '<option value="' . $row['id'] . '" selected>' . $row['name'] . '</option>';
        } else
            $ret .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
    return $ret;
}
function hasSolved($userid, $problemid)
{
    $sql = "SELECT COUNT(*) as done FROM solved WHERE user_id = $userid AND problem_id = $problemid AND status = 1";
    $res = myquery($sql);
    $row = mysqli_fetch_assoc($res);
    return $row['done'] > 0;
}
function updateUser($uid, $curFullName, $country, $organization, $curAbt, $img)
{
    $sql = "UPDATE user
    SET 
        fullname = '$curFullName',
        country = '$country',
        organization = '$organization',
        about = '$curAbt',
        img = '$img'
    WHERE id = $uid";
    myquery($sql);
}
function toggleSolve($userid, $problemid)
{
    $sql = "SELECT * FROM solved WHERE user_id = $userid AND problem_id = $problemid";
    $res = myquery($sql);
    if (mysqli_num_rows($res) == 1) {
        $sql = "UPDATE solved SET status = 1-status, date_solved = NOW()
                WHERE user_id = $userid AND problem_id = $problemid";
        myquery($sql);
    } else {
        $sql = "INSERT INTO solved(user_id,problem_id,date_solved,status)
                    VALUES($userid,$problemid,NOW(),1)";
        myquery($sql);
    }
}
//function for users
function getUsers($offset, $limit)
{
    $sql = "SELECT * FROM user ORDER BY join_date DESC LIMIT {$offset},{$limit} ";
    return myquery($sql);
}
function getCountry($id)
{
    if ($id == 0) return '-';
    $sql = "SELECT * FROM country WHERE id = $id";
    $res = mysqli_fetch_assoc(myquery($sql));
    return $res['nicename'];
}

function getSolveCount($id)
{
    $sql = "SELECT COUNT(*) as solve_count from solved WHERE problem_id = $id AND status = 1";
    $query = myquery($sql);
    $res = mysqli_fetch_assoc($query);
    return $res['solve_count'];
}
function getBlogCount($id)
{
    $sql = "SELECT COUNT(*) as cnt FROM blog WHERE user_id = $id";
    $res = myquery($sql);
    $row = mysqli_fetch_assoc($res);
    return $row['cnt'];
}
function getProblemCount($id)
{
    $sql = "SELECT COUNT(*) as cnt FROM problem WHERE user_id = $id";
    $res = myquery($sql);
    $row = mysqli_fetch_assoc($res);
    return $row['cnt'];
}
function getContribution($id)
{
    $problemCnt = getProblemCount($id);
    $solveCnt = getSolveCount($id);
    $blogCnt = getBlogCount($id);

    return $solveCnt + 2 * $problemCnt + 5 * $blogCnt;
}

function getTopContributors()
{
    $sql = "SELECT c_table.*,COALESCE((c_table.s_cnt*1+c_table.b_cnt*5+c3.p_cnt*2),0) as cntbr FROM (SELECT t.*,COALESCE(t.solve_cnt,0) as s_cnt,COALESCE(c2.b_cnt,0) as b_cnt  FROM ( SELECT u.*,COALESCE(s_cnt,0)as solve_cnt FROM user as u LEFT JOIN ( SELECT user_id,COUNT(user_id) as s_cnt FROM solved WHERE status = 1) c1 ON u.id = c1.user_id) t LEFT JOIN ( SELECT user_id,COUNT(user_id) as b_cnt FROM blog WHERE status = 1 GROUP BY user_id) c2 ON t.id = c2.user_id) c_table LEFT JOIN (SELECT user_id,COUNT(user_id) as p_cnt  FROM problem WHERE status = 1 GROUP BY user_id)c3 ON c_table.id = c3.user_id ORDER BY cntbr DESC LIMIT 0,10";
    return myquery($sql);
}
