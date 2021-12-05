<?php

$rootName = "graduation-project-web";

require_once("config.php");

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $loggedUserID = $_SESSION["id"];

    $queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = $loggedUserID");
    $queryUserInfo->execute();

    $getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

    $loggedUsername = $getUserInfo["username"];
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/05e9384002.js" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link href="/graduation-project-web/assets/css/layout.css" rel="stylesheet" />
    <link href="/graduation-project-web/assets/css/fonts.css" rel="stylesheet" />
    <link href="/graduation-project-web/assets/css/colors.css" rel="stylesheet" />
    <link href="/graduation-project-web/assets/css/images.css" rel="stylesheet" />

    <title><?php echo $pageTitle; ?></title>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/<?php echo $rootName; ?>/anasayfa">Site Adı</a>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarToggler"
                    aria-controls="navbarToggler"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarToggler">
                    <ul class="navbar-nav">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/<?php echo $rootName; ?>/anasayfa">Anasayfa</a>
                        </li> -->
                    </ul>
                    <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link active dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="badge bg-light text-dark font-16">
                                    <img 
                                        style="border-radius: 100%;" 
                                        src="/<?php echo $rootName; ?>/assets/img/profile_photos/<?php echo $getUserInfo["profile_photo"]; ?>" 
                                        width="25px" height="25px" />
                                    <?php echo $getUserInfo["username"]; ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg-end dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a class="dropdown-item" href="/<?php echo $rootName; ?>/messages/gelen-kutusu"><i class="fas fa-inbox"></i> Mesajlar</a></li>
                                <li><a class="dropdown-item" href="/<?php echo $rootName; ?>/user/<?php echo $loggedUsername; ?>"><i class="fas fa-user"></i> Profilim</a></li>
                                <li><a class="dropdown-item" href="/<?php echo $rootName; ?>/ayarlar"><i class="fas fa-cog"></i> Ayarlar</a></li>
                                <li><a class="dropdown-item" href="/<?php echo $rootName; ?>/oturumu-kapat"><i class="fas fa-sign-out-alt"></i> Oturumu Kapat</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/<?php echo $rootName; ?>/giris-yap"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/<?php echo $rootName; ?>/uye-ol"><i class="fas fa-user-plus"></i> Üye Ol</a>
                        </li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
