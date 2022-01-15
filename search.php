<?php
include './includes/header.inc.php';
$query = $_GET['query'];
?>

<main class="container">
    <h2>Search Result for "<?= $query; ?>"</h2>
    <div class="blog">
        <h4>Blogs Related to "<?= $query; ?>"</h4>
        <ul class="list-group">
            <?php
            $sql = "SELECT * FROM blog WHERE title LIKE '%$query%' LIMIT 0,5";
            $res = myquery($sql);
            while ($row = mysqli_fetch_assoc($res)) {
                $id = $row['id'];
                $title = $row['title'];
            ?>
                <li class="list-group-item"><a href="./blog.php?id=<?= $id; ?>"><?= $title; ?></a></li>
            <?php
            }
            ?>
        </ul>
        <button class="btn btn-sm m-2 btn-secondary" onclick="window.location.href = './blogs.php'">See All</button>
    </div>
    <div class="problem">
        <h4>Problems matching "<?= $query; ?>"</h4>
        <ul class="list-group">
            <?php
            $sql = "SELECT * FROM problem WHERE name LIKE '%$query%' LIMIT 0,5";
            $res = myquery($sql);
            while ($row = mysqli_fetch_assoc($res)) {
                $id = $row['id'];
                $name = $row['name'];
                $url = $row['url'];
            ?>
                <li class="list-group-item"><a href="<?= $url; ?>"><?= $name; ?></a></li>
            <?php
            }
            ?>
        </ul>
        <button class="btn btn-sm m-2 btn-secondary" onclick="window.location.href = './problems.php'">See All</button>
    </div>
    <div class="problem">
        <h4>Users matching "<?= $query; ?>"</h4>
        <ul class="list-group">
            <?php
            $sql = "SELECT * FROM user WHERE username LIKE '%$query%' LIMIT 0,5";
            $res = myquery($sql);
            while ($row = mysqli_fetch_assoc($res)) {
                $id = $row['id'];
                $name = $row['username'];
            ?>
                <li class="list-group-item"><a href="./user.php?id=<?=$id;?>"><?= $name; ?></a></li>
            <?php
            }
            ?>
        </ul>
        <button  class="btn btn-sm m-2 btn-secondary" onclick="window.location.href = './users.php'">See All</button>
    </div>
</main>

<script>
    sessionStorage.setItem("query","<?=$query;?>")
    function seeblog(){
        
    }
</script>