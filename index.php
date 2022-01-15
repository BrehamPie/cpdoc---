<?php
include './includes/header.inc.php';
$votebutton = 'disabled';
$userid = '';
if (isset($_SESSION['userid'])) {
    $votebutton = '';
    $userid = $_SESSION['userid'];
}

?>

<main>
    <div class="container-fluid row ">
        <div class="col-9">
            <?php
            $res = getBlogs(0, 10);
            $blogCount = 0;
            while ($row = mysqli_fetch_assoc($res)) {
                $blogid = $row['id'];
                $heading = $row['title'];
                $body = keep15Line($row['body']);
                $type = $row['type'];
                $author_id = $row['user_id'];
                $authorData = getUserData($author_id);
                $authorName = $authorData['username'];
                $date_created = $row['date_created'];
                $date_created = date("F j, Y", strtotime($date_created));
                $status = $row['status'];
                if ($status == 0) continue;
                $blogCount++;
                if ($blogCount == 10) break;
            ?>
                <div class="one-post border m-2 p-3">
                    <div class="">
                        <h3><?= $heading; ?></h3>
                        <div class="d-flex justify-content-between">
                            <p>By <a href="./user.php?id=<?= $author_id; ?>"><?= $authorName; ?></a></p>
                            <p><?= $date_created; ?></p>
                        </div>
                    </div>
                    <?= $body; ?>
                    <a href="./blog.php?id=<?= $blogid; ?>" class="btn btn-outline-secondary">Read More</a>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex justify-content-center">
                            <div class="left">
                                <button class="btn btn-outline-success" <?= $votebutton; ?> onclick="sendVote(<?= $blogid; ?>,1,<?= $userid; ?>)"><i class='bx bxs-up-arrow' id="up-vote"></i></button>
                            </div>
                            <div class="vote-count mx-1 ">
                                <p class="p-2"><?= getVote($blogid); ?></p>
                            </div>
                            <div class="left">
                                <button class="btn btn-outline-danger" <?= $votebutton; ?> onclick="sendVote(<?= $blogid; ?>,0,<?= $userid; ?>)"><i class='bx bxs-down-arrow' id="down-vote"></i></button>
                            </div>

                        </div>
                        <div class="user-bottom d-flex align-item-center justify-content-between gap-5">
                            <div class="user-box d-flex">
                                <div class="icon-user">
                                    <i class='bx bx-user'></i>
                                </div>
                                <div class="user-name ps-1">
                                    <p><a href="./user.php?id=<?= $author_id; ?>"><?= $authorName; ?></a></p>
                                </div>
                            </div>
                            <div class="comment-box d-flex">
                                <div class="icon-user">
                                    <i class='bx bx-chat'></i>
                                </div>
                                <div class="user-name ps-1">
                                    <p>200</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
        <div class="col-3">
            <div class="upcoming-contest mt-2">
                <div class="contest-head border">
                    <h5 class="text-center">Upcoming Contests</h5>
                </div>
                <div class="contestlist">

                </div>
            </div>
            <div class="profile-overview border mt-4" style="display: none;">
                <?php
                $username = '';
                $userid = '';
                $img = '';
                if (isset($_SESSION['userid'])) {

                    $userid = $_SESSION['userid'];
                    $userData = getUserData($userid);
                    $userName = $userData['username'];
                    $img = './assets/img/users/' . $userData['img'];
                }
                ?>
                <h5 class="text-center"><a href="./user.php?id=<?= $userid; ?>"><?= $userName; ?></a></h5>
                <div class="d-flex justify-content-between">
                    <div class="data-box">
                        <ul>
                            <li><a href="./user.php?id=<?= $userid; ?>&dashboard">Dashboard</a></li>
                            <li><a href="./user.php?id=<?= $userid; ?>&blogs">Blogs</a></li>
                            <li><a href="./user.php?id=<?= $userid; ?>&problems">Problems</a></li>
                            <li><a href="./user.php?id=<?= $userid; ?>&setting">Setting</a></li>
                            <li><a href="./user.php?id=<?= $userid; ?>">Friends</a></li>
                        </ul>
                    </div>
                    <div class="user-pic m-1 p-1">
                        <img src="<?= $img; ?>" alt="" height="110px">
                    </div>
                </div>
            </div>
            <div class="recent-problems mt-5">
                <div class="contest-head border">
                    <h5 class="text-center">Recently Added Problems</h5>
                </div>
                <?php
                $probs = getProblems(0, 7);
                while ($row = mysqli_fetch_assoc($probs)) {
                    $id = $row['id'];
                    $url = $row['url'];
                    $name = $row['name'];
                ?>
                    <div class="contest-body border">
                        <div class="d-flex">
                            <div class="judge-img d-flex align-items-center justify-content-center">
                                <img src="./assets/img/oj/<?= $id; ?>.png" alt="" height="50px" class="p-2 m-2">
                            </div>
                            <div class="contest-name d-flex align-items-center justify-content-center">
                                <a class=" mb-0" href="<?= $url; ?>"><?= $name; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
            <div class="top-contributor">
                <div class="contest-head border mt-5">
                    <h5 class="text-center">Top Contributors</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Handle Name</th>
                                <th>Contributions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cons = getTopContributors();
                            $cnt = 0;
                            while ($row = mysqli_fetch_assoc($cons)) {
                                $cnt++;
                                $id = $row['id'];
                                $username = $row['username'];
                                $cntbr = $row['cntbr'];
                            ?>
                                <tr>
                                    <td><?= $cnt; ?></td>
                                    <td><a href="./user.php?id=<?= $id; ?>"><?= $username; ?></a></td>
                                    <td><?= $cntbr; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function getFromAPI(url, callback) {
        var obj;
        fetch(url)
            .then(res => res.json())
            .then(data => obj = data)
            .then(() => callback(obj))
    }
    getFromAPI('https://kontests.net/api/v1/all', showList);

    function dhm(ms) {
        if (ms < 0) return "Running";
        const days = Math.floor(ms / (24 * 60 * 60 * 1000));
        const daysms = ms % (24 * 60 * 60 * 1000);
        const hours = Math.floor(daysms / (60 * 60 * 1000));
        const hoursms = ms % (60 * 60 * 1000);
        const minutes = Math.floor(hoursms / (60 * 1000));
        if (minutes < 10) adm = '0';
        else adm = '';
        if (hours < 10) adh = '0';
        else adh = '';
        const minutesms = ms % (60 * 1000);
        const sec = Math.floor(minutesms / 1000);
        if (sec < 10) ads = '0';
        else ads = '';
        return days + " : " + adh + hours + " : " + adm + minutes + " : " + ads + sec + " ";
    }

    function showList(arrOfObjs) {
        var res = JSON.parse(JSON.stringify(arrOfObjs));
        var Contest = res.filter(e => ((e['site'] == 'CodeForces') || (e['site'] == 'Toph') || (e['site'] == 'CodeChef') || (e['site'] == 'AtCoder')));
        var curTime = new Date();
        var startTimeArray = [];
        var fullText = '';
        var count = 1;
        Contest.forEach(e => {
            var startTime = new Date(e['start_time']);
            var fullDate = startTime.getDate() + '/' + (startTime.getMonth() + 1) + '/' + startTime.getFullYear();
            var Hour = startTime.getHours();
            var ampm = "AM";
            if (Hour > 12) {
                ampm = "PM";
                Hour -= 12;
            }
            if (Hour == 0) Hour = 12;
            var fullTime = Hour + ':' + startTime.getMinutes() + ':' + startTime.getSeconds() + '0 ' + ampm;
            var Remaining = startTime - curTime;
            if (Remaining > 0 && count < 4) {
                console.log(e);
                startTimeArray.push(startTime);
                var imgsrc = './assets/ojlogo/';
                if (e['site'] == 'CodeForces') imgsrc += 'cfmini.png';
                if (e['site'] == 'CodeChef') imgsrc += 'ccmini.png';
                if (e['site'] == 'AtCoder') imgsrc += 'atcmini.png';
                if (e['site'] == 'Toph') imgsrc += 'tophmini.png';
                var add = `<div class="contest-body border"> <div class="row">
        <div class="judge-img col-2 flex align-item-center justify-content-center">
                    <img src="` + imgsrc + `" alt="" height="60px" class="p-2">
                </div>
                <div class="contest-name flex  col-9">
                    <p class="text-center  mb-0"><a href="` + e['url'] + `">` + e['name'] + `</a>
                    </p>
                    <p class="text-center ctime">` + dhm(Remaining) + `</p>
                </div>
            </div>
        </div>`;
                fullText += add;
                count++;
            }
            $('.contestlist').html(fullText);
            console.log($('.contestlist :nth-child(1n)>p:nth-child(2n)').text());

        });
        var x = setInterval(function() {
            var now = new Date().getTime();
            var cur = 0;
            var allbox = document.querySelectorAll('.contestlist :nth-child(1n)>p:nth-child(2)');
            allbox.forEach(e => {
                e.innerHTML = dhm(startTimeArray[cur] - now);
                cur++;
            })
        }, 500);



    }

    if (sessionStorage.getItem('user') == 'login') {
        $('.profile-overview').toggle();
    }
</script>
<?php
include './includes/footer.inc.php';
?>