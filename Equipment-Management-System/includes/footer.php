<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<div id="contact">
    <section class="footer-section" style="border-top: 5px solid #1B4D3E;"> <!-- Updated line color -->
        <div class="container">
            <div class="row">
                <div class="col-12" style="text-align: center;">
                    <!-- Content visible to all users on index.php -->
                    <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') { ?>
                        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                            <!-- Left-aligned content -->
                            <div style="text-align: left;">
                                DEPARTMENT OF COMPUTER STUDIES © 2024-2025 <br>
                                <a href="https://www.facebook.com/officialstrosecollege2002" style="text-decoration: none;" target="_blank">
                                    <i class="fab fa-facebook"></i> SRCEFI FACEBOOK
                                </a>
                            </div>

                            <!-- Right-aligned content -->
                            <div style="text-align: right;">
                                DEVELOPER CONTACTS: <br>
                                    <i class="fa-solid fa-phone"></i> +9666505315 |
                                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=kimberly.aquinez@gmail.com" style="text-decoration: none;">
                                    <i class="fa-solid fa-envelope"></i> EMAIL
                                </a>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Content visible only to admin when logged in -->
                    <?php if (isset($_SESSION['alogin']) && $_SESSION['alogin'] == true) { ?>
                        <div style="text-align: center; margin-top: 10px;">
                            DEPARTMENT OF COMPUTER STUDIES © 2024-2025
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>