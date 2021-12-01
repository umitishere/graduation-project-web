<?php

require_once("config.php");

if (!isset($_SESSION)) {
    session_start();
}

$sessionID = $_SESSION["id"];

$queryUserInfo = $pdo->prepare("SELECT * FROM users WHERE id = '$sessionID'");
$queryUserInfo->execute();

$getUserInfo = $queryUserInfo->fetch(PDO::FETCH_ASSOC);

$username = $getUserInfo["username"];

if (isset($_POST["update_username"])) {

    $newUsername = htmlspecialchars($_POST["new_username"], ENT_QUOTES);
    $username_err = "";

    $query = $pdo->prepare("UPDATE users SET username = '$newUsername' WHERE id = '$sessionID'");

    // Validate username
    if(empty($newUsername)) {
        $username_err = "Lütfen bir kullanıcı adı girin.";
    } else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($newUsername))) {
        $username_err = "Kullanıcı adı sadece harf, sayı ve alt çizgiden oluşmalıdır ve Türkçe karakter içermemelidir.";
    } else if(strlen(trim($newUsername)) < 6 || strlen(trim($newUsername)) > 16) {
        $username_err = "Kullanıcı adı en az 6, en fazla 16 karakterden oluşmalıdır.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($newUsername);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if ($stmt->rowCount() == 1) {
                    $username_err = "Bu kullanıcı adı daha önce alınmış.";
                }
            }

            // Close statement
            unset($stmt);
        }
    }

    if (empty($username_err)) {
        $queryExecute = $query->execute();
    }

    if (!empty($username_err)) {
        header("Location: ../$username?usernameChangeError=$username_err");
    } else {
        header("Location: ../$newUsername");
    }



}

if (isset($_POST["update_biography"])) {

    $newBiography = htmlspecialchars($_POST["new_biography"], ENT_QUOTES);

    $query = $pdo->prepare("UPDATE users SET biography = '$newBiography' WHERE id = '$sessionID'");

    if (strlen(trim($newBiography)) < 181) {
        $queryExeute = $query->execute();
    }

    header("Location: ../$username");

}

if (isset($_POST["update_profile_photo"])) {

    // Delete previous photo from storage
    $previousPhoto = $getUserInfo["profile_photo"];

    if ($previousPhoto != "profile_default.png") {
        unlink("../assets/img/profile_photos/$previousPhoto");
    }

    // Check Image Detail And Insert Data
    $target_dir = "../assets/img/profile_photos/";
    $randomNumber = rand(1, 9999999);
    $target_file = $target_dir . $randomNumber . basename($_FILES["new_profile_photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Üzgünüz, bu dosya zaten mevcut.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["new_profile_photo"]["size"] > 9000000) {
        echo "Üzgünüz, görselin boyutu en fazla 9 MB olmalıdır.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Üzgünüz, sadece JPG, JPEG, PNG & GIF türünde dosyaları yükleyebilirsiniz.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Üzgünüz, fotoğrafınızı yükleyemedik.";
    // if everything is ok, try to upload file
    } else {

        if (move_uploaded_file($_FILES["new_profile_photo"]["tmp_name"], $target_file)) {

            $imgName = $randomNumber . basename($_FILES["new_profile_photo"]["name"]);

            $query = $pdo->prepare("UPDATE users SET profile_photo = '$imgName' WHERE id = '$sessionID'");

            $queryExeute = $query->execute();

        } else {
            echo "Fotoğraf yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.";
        }
    }

    header("Location: ../$username");

}

?>