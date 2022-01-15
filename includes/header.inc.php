<?php
require_once './includes/db.inc.php';
require_once './includes/functions.inc.php';
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./style/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Multiple Select From Dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Document</title>
</head>

<body>
    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php">
                <img src="./assets/logo.png" alt="" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item ">
                        <a class="nav-link" aria-current="page" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./blogs.php">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./problems.php">Problems</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contests.php">Contests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./users.php">Users</a>
                    </li>
                </ul>
                <form class="d-flex" method="GET" action="search.php">
                    <input class="form-control me-2" type="search" placeholder="Search" name="query">
                    <button class="btn btn-outline-secondary" type="submit">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUJJREFUSEu9le0xBEEURc9GQAhEgAzIgAjIABmQARmQgQwQARnYEIiAOlWv1avR3TO7NWOq9sdOd9/z+r6PWbHws1pYnx5gD7gEjoHDCOQdeAHugfWU4FqAuxDvabjnegxSAxjlQRx8BBTynY83uQLO042OepAhoET+FdYU4aGGIK3aCbuEVp8M0POP2GVULfEiJOQt/uy3cpIBJXoT2IxoEOZD2NU8kwHF+ynRD2/h2WouMuA7Tm3aG91zcwEsit1aluey6DWq7g/jX5O8bZlqjyVbHR2tRvsETjq9oOBz+H4L3ExptLInjwrr3BrPo8IBeJEEXTMYg+rmIC+ODTttcc9pzK0mZGxc29GOa4efogr5U1zPLU1nkutVyKZNVXOhC5kDIDRDzoCnEslcgALRzl9xX84JGP0e9D5MW68tfoMfCXxMGRHr0pcAAAAASUVORK5CYII=" />
                    </button>
                </form>
                <div id="before-sign">
                    <button class="btn btn-sm btn-outline-primary m-2" onclick="goAuth('signup')">Sign up</button>
                    <button class="btn btn-sm btn-outline-primary m-2" onclick="goAuth('login')">Login</button>
                </div>
                <div id="after-sign" style="display: none;">
                    <button class="btn btn-sm btn-outline-primary m-2" onclick="window.location.href = './user.php?id=<?= $_SESSION['userid']; ?>'">Profile</button>
                    <button class="btn btn-sm btn-outline-primary m-2" onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </nav>
    <script>
        if (sessionStorage.getItem("user") == "login") {
            $('#before-sign').css("display", "none");
            $('#after-sign').css("display", "");
        }

        function goAuth(x) {
            sessionStorage.setItem("todo", x);
            window.location.assign("./auth.php");
        }

        function logout() {
            sessionStorage.removeItem("user");
            window.location.href = './includes/logout.inc.php';
        }
        $(document).ready(function() {
            var url = window.location;
            $('ul.navbar-nav a[href="' + url + '"]').addClass('active');
            $('ul.navbar-nav a').filter(function() {
                return this.href == url;
            }).addClass('active');
        });
    </script>