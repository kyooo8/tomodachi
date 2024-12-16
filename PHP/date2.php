<?php
session_start();
require './common/header.php';
require './common/card_component.php';
require './common/db-connect.php'; 
require './common/searchSchool.php';
$loggedInUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$user_id = $_SESSION['user_id'];
$sql = "SELECT school_id FROM users WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$school_id = $stmt->fetchColumn();
?>


<!-- 検索欄 -->
<div class="contant">
        <form id="schoolSearchForm" action="search.php" method="get" class="mb-4 search">
            <div class="input-group">
                <input type="text" name="school_name" id="school_name" class="form-control" placeholder="学校名を入力">
            </div>
        </form>
        <div id="school_predictions"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../js/search.js"></script>
<!-- 検索欄ここまで-->


<!-- ランダムユーザー表示 -->
    <?php
        if($loggedInUser != null){
            $sql = "SELECT user_id, profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE is_private = 0 AND user_id != :user_id ORDER BY RAND() LIMIT 16";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $loggedInUser, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            $sql = "SELECT user_id, profile_image, user_name, date_of_birth, gender, school_id FROM users WHERE is_private = 0 ORDER BY RAND() LIMIT 16";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        echo "<h2>いいねした人</h2>";
        echo '<div class="user_cards">';
        while($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            renderCard($user['user_id'], $user['profile_image'], $user['user_name'], $user['date_of_birth'], $user['gender'], $user['school_id'], $user_id);
        }
        echo '</div>';
    ?>
<!-- ランダムユーザー表示ここまで -->
      
<!-- いいね処理 -->
<script src="../js/likeButtonHandler.js"></script>

    <?php
        $conn = null;
    ?>
</div>
<style>
    .contant {
        height: 100vh;
        padding: 15px 20px;
        overflow: scroll;
        /*IE(Internet Explorer)・Microsoft Edgeへの対応*/
        -ms-overflow-style: none;
        /*Firefoxへの対応*/
        scrollbar-width: none;
    }
    /*Google Chrome、Safariへの対応*/
    .contant::-webkit-scrollbar {
        display: none;
    }
    .contat .search {
        width: 80%;
        margin: 10px auto 0 auto;
    }
    .user_cards {
        display: flex;
        padding: 10px;
        overflow-x: scroll;
        margin-bottom: 30px;
        /*IE(Internet Explorer)・Microsoft Edgeへの対応*/
        -ms-overflow-style: none;
        /*Firefoxへの対応*/
        scrollbar-width: none;
    }
    /*Google Chrome、Safariへの対応*/
    .user_cards::-webkit-scrollbar {
        display: none;
    }
    .user_card {
        position: relative;
        margin-left: 20px;
        width: 200px;
        height: 300px;
        border: 1px solid #dadada;
        border-radius: 5px;
        background-color: white;
        text-align: center;
    }
    .user_card img {
        border-radius: 50%;
        height: 100px;
        width: 100px;
        object-fit: cover;
        margin: 15px auto 0 auto;
    }
    .card-body {
        text-align: right;
        font-weight: 400;
        font-weight: 400px;
        background:white;
        border: none;
        position: relative;
        transform: translateY(30%);
        width: 80%;

    }
    .like-btn {
        position:absolute;
        top: 40%;
        right: 10%;
        background: white;
        border: none;
        color: #e62748;
        text-align: right;
        cursor: pointer;
    }
    .card-body .user_name {
        font-size: 23px;
        font-weight: 600;
        text-align: center;
    }
    .card-body .school {
        font-size: 13px;
        text-align: center;
    }
</style>

<?php require './common/footer.php'; ?>
