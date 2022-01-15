<?php
include './includes/header.inc.php';
?>

<body>
    <div class="login container">
        <div class="login_content">
            <div class="login_img">
                <img src=".\assets\img\img-login.svg" alt="">

            </div>
            <div class="login_forms">
                <form action="#" class="login_registration" id="login_in" method="POST">
                    <h1 class="login_title">Sign In</h1>
                    <div class="login_box">
                        <i class='bx bxs-user login_icon'></i>
                        <input type="text" placeholder="Username" name='username' id="username" class="login_input">
                    </div>

                    <div class="login_box">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="password" id="password" placeholder="Password" class="login_input">
                    </div>
                    <a href="#" class="login_forgot">Forgot Passwrod?</a>
                    <p class='warning_sign' id='warning_sign'></p>
                    <div class="d-grid gap-2">
                        <button type="submit" class="login_button btn btn-block" id='login'>Sign In</button>
                    </div>

                    <div><span class="login_account">Don't have an Account? </span>
                        <span class="login_signup" id="sign-up">Sign Up </span>
                    </div>
                </form>
                <form action="" class="login_create none" id="login_up" method="POST">
                    <h1 class="login_title">Create Account</h1>
                    <div class="login_box">
                        <i class='bx bxs-user login_icon'></i>
                        <input type="text" placeholder="Username" name='username' class="login_input">

                    </div>

                    <div class="login_box">
                        <i class='bx bx-at'></i>
                        <input type="text" placeholder="Email" name="email" class="login_input">
                    </div>

                    <div class="login_box">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="password" id="up_password" placeholder="Password" class="login_input">
                    </div>

                    <div class="login_box">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="repassword" id="repassword" placeholder="Confirm Password" class="login_input">
                    </div>
                    <p class='warning_sign_up' id='warning_signup'></p>
                    <div class="d-grid gap-2">
                        <button type="submit" class="login_button btn btn-block" id='registration'>Sign Up</button>
                    </div>
                    <div><span class="login_account">Already Have an Account? </span>
                        <span class="login_signin" id="sign-in">Sign In</span>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="./scripts/authentication.js"></script>
    <?php
    include './includes/footer.inc.php';
    ?>