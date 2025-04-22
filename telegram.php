<?php
session_start(); // Oturum başlat

// Kullanıcı oturumu kontrol et ve oturumda user_id ayarla
if (!isset($_SESSION['user_id'])) {
    // user_id oturumda yoksa login.php'ye yönlendir
    header("Location: login.php");
    include 'login.php';
     
}


$test=$_SESSION['user_id'];
$userId = $_SESSION['user_id'];
 

// Veritabanı bağlantısı
include('db.php');

// Kullanıcı bilgilerini veritabanından al
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $money = $user['money'];
    $rrcash = $user['rrcash'];
    $perkTime = $user['perk_time'];
    $premiumtime = $user['premium_time'];
    $tahminhakki = $user['tahmin_oynu_hakki'];
} else {
    // Kullanıcı bulunamazsa hata ver
    echo "ERror.";
    exit();
}
$sql = "SELECT notification FROM articles WHERE username = ? AND notification = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username); // `user_id` yerine `username` kullanılıyor
$stmt->execute();
$result = $stmt->get_result();

// Kullanıcının makale bildirimini kontrol et
$notification_message = '';
if ($result->num_rows > 0) {
    // Eğer `notification` değeri 1 ise, mesajı göster
    $notification_message = "Article Quest Done. You have earned 1 coin.";
    
    // `notification` değerini sıfırlayarak mesajın tekrar gösterilmesini engelle
    $update_sql = "UPDATE articles SET notification = 0 WHERE username = ? AND notification = 1";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("s", $username);
    $update_stmt->execute();
}



// kapama 
$stmt->close();
$conn->close();

// Mevcut zaman ve bekleme süresi kontrolü
$currentUnixTime = time();
$timeLeft = $perkTime - $currentUnixTime;
$timeLeftFormatted = gmdate("H:i:s", $timeLeft);

 
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Web App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>


    
    <script src="site sahibi @ telegram adresi ona yazın"></script>
<!-- Yandex.RTB -->
<script>window.yaContextCb=window.yaContextCb||[]</script>
<script src="https://yandex.ru/ads/system/context.js" async></script>



<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5381747415518000"
     crossorigin="anonymous"></script>


     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Slim yerine tam jQuery -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    </head>

<body>

    <header class="bg-dark text-white text-center p-1">
        <h1>Hello, Welcome to RRFF Simulation Game!</h1>
    </header>
    <main class="container mt-4">
        <?php if (!empty($notification_message)): ?>
            <div class="alert alert-success">
                <?php echo $notification_message; ?>
            </div>
        <?php endif; ?>

    </main>
 
        <div id="user-info" class="mb-4">
            <div class="card mx-auto" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($firstName); ?></h5>
                    <p class="card-text">Username: <?php echo htmlspecialchars($username); ?></p>
                    <p class="card-text">RRFFCOİN: <?php echo number_format($money); ?> </p>
                    <p class="card-text">RRCASH: <?php echo number_format($rrcash); ?>  <i id="inforrcash-btn" class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" title="İnfo RRCASH"></i></p>
                    <p class="card-text "> <?php $kalantime=$premiumtime-time();if ($kalantime>0) {$expirationDate = date("Y-m-d H:i", $premiumtime); echo "Premium Time: $expirationDate";}?> </p>
                    
                    <button id="shop-btn" class="btn btn-warning">Coin Market</button>
                    
                    <?php
// user_id kontrolü ile admin panel butonu ekle
if ($userId == 5377130946 || $userId == 599337363) {
    echo '<button id="admin-panel-btn" class="btn btn-danger">Admin Panel</button>';
}
?>
                </div>
            </div>
        </div>
        <div id="content-area" class="mt-4">
        <button id="tahmin-btn" class="btn btn-warning">Match Prediction Game</button>
        <button id="daily-reward-btn" class="btn btn-warning">Collect Daily Reward</button>
        <br><br>
        <button id="articlequest-btn" class="btn btn-warning">Daily Article Quest</button>
        <br><br>

        <button id="rrffu19-btn" class="btn btn-warning">RRFF U19 (BETA)</button>

        <br><br>
        <button id="teams-btn" class="btn btn-success">Teams Player All Information</button>
        <br><br>

        <button id="turkiye-btn" class="btn btn-success">RRFF Türkiye &#x1f1f9;&#x1f1f7; </button>
        <br><br>

        <button id="italypuan-btn" class="btn btn-success">RRFF Italia Serie </button>
        <br><br>
        <button id="fransapuan-btn" class="btn btn-success">RRFF Française Ligue </button>
        <br><br>
     
        <button id="spain-btn" class="btn btn-success">RRFF Espana Primera Liga </button>
        <br><br>

        <!-- Daily Reward Button -->
  
        </div>
        <div id="content-area" class="mt-4"></div>
















        
        <script>



document.getElementById('inforrcash-btn').addEventListener('click', function() {
    // Popup oluştur
    const popup = document.createElement('div');
    popup.innerHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
            <div style="background-color: white; padding: 20px; border-radius: 10px; text-align: center;">
            <button id="close-popup" class="btn btn-primary">Close</button>
            <h3>Premium Information</h3>
                <p>1 RRCASH = 1kkk (RRMONEY). RRCASH allows you to buy premium. When you come to the bot and type /deposit 8, copy the amount to be deposited from there and paste it into the link. You will deposit 8kkk, and you will receive 8 RRCASH.</p>
                <h3>Premium Bilgisi</h3>
                <p>1 RRCASH = 1kkk(RRMONEY) RRCASH ile premium almanı sağlar.Bota gelip /deposit 8 yazdığınızda orda yatırılması gereken tutarı kopyalıp linke attığınızda 8 kkk ücret yatar 8 RRCASH sahip olursunuz</p>
                
            </div>
        </div>
    `;
    
    // Popup'ı sayfaya ekle
    document.body.appendChild(popup);

    // Close butonuna tıklayınca popup'ı kaldır
    document.getElementById('close-popup').addEventListener('click', function() {
        document.body.removeChild(popup);
    });
});

        </script>
     
    <script>
        
        
        function sendUsernameToServer(username) {
            fetch('tracker.php?username=' + encodeURIComponent(username))
                .then(response => response.text())
                .then(data => {
                    console.log('User logged:', data);
                })
                .catch(error => {
                    console.error('Error logging user:', error);
                });
        }
        document.getElementById('tahmin-btn').addEventListener('click', function () {
            window.location.href = 'mactahminoynu.php';
        });
        document.getElementById('spain-btn').addEventListener('click', function () {
            window.location.href = 'espana.php';
        });

        document.getElementById('italypuan-btn').addEventListener('click', function () {
            window.location.href = 'italia.php';
        });

        document.getElementById('fransapuan-btn').addEventListener('click', function () {
            window.location.href = 'française.php';
        });

        document.getElementById('teams-btn').addEventListener('click', function () {
            window.location.href= 'teams.php';
        });

        document.getElementById('turkiye-btn').addEventListener('click', function () {
            window.location.href= 'turkiye.php';
        });
        document.getElementById('shop-btn').addEventListener('click', function () {
            window.location.href= 'shop.php';
        });

        document.getElementById('rrffu19-btn').addEventListener('click', function () {
            window.location.href= 'rrffu19.php';
        });

        document.getElementById('articlequest-btn').addEventListener('click', function () {
    const articleLink = prompt("Please enter the article link:");
    if (articleLink) {
        fetch('submit_article.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `article_link=${encodeURIComponent(articleLink)}&completed=0&user_id=<?php echo $userId; ?>`
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});

   
        // Daily Reward Button Handler

    </script>

<!-- Makale Linki Giriş Modal -->






<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kullanıcının duyuruyu görüp görmediğini kontrol et
    fetch('check_announcement.php')
        .then(response => response.json())
        .then(data => {
            if (data.viewedannouncement === 0) {
                // Eğer kullanıcı duyuruyu görmemişse, popup aç
                showAnnouncementPopup();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

    // Popup'ı gösteren fonksiyon
    function showAnnouncementPopup() {
        const popup = document.createElement('div');
        popup.innerHTML = `
            <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center;">
            <div style="background-color: white; padding: 20px; border-radius: 10px; text-align: center;">
            <button id="close-popup" class="btn btn-primary">Close</button>
    <h3>Announcement</h3>
    <p>Hello everyone, with the /withdraw feature, Premium Purchase has been added 1 RRCASH = 1kkk. You can learn about premium privileges from the info box..</p>
    <h3>Duyuru</h3>
    <p>Herkese merhaba, /withdraw özelliği ile birlikte RRFFCASH ile Premium Satın Alma eklendi 1 RRCASH = 1kkk. Premium ayrıcalıklarını info kutucuğundan öğrenebilirsiniz.</p>

</div>
            </div>
        `;
        document.body.appendChild(popup);

        document.getElementById('close-popup').addEventListener('click', function() {
            document.body.removeChild(popup);
            // Kullanıcı popup'ı gördükten sonra durumu güncelle
            fetch('update_announcement.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Duyuru durumu güncellendi.');
                } else {
                    console.error('Durum güncellenemedi.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('daily-reward-btn').addEventListener('click', function () {
                fetch('daily_reward.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'daily_reward=1'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                    } else if (data.status === 'already_taken') {
                        alert(data.message);
                    } else {
                        alert('Hata: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Hata:', error);
                });
            });
        });
    </script>


























    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <!-- Font Awesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- Yandex.RTB R-A-10899779-1 -->
    <div id="yandex_rtb_R-A-10899779-1"></div>
    <script>
    window.yaContextCb.push(() => {
        Ya.Context.AdvManager.render({
            "blockId": "R-A-10899779-1",
            "renderTo": "yandex_rtb_R-A-10899779-1"
        })
    })
    </script>

    <!-- Yandex.RTB R-A-10898300-1 -->
    <script>
    window.yaContextCb.push(() => {
        Ya.Context.AdvManager.render({
            "blockId": "R-A-10898300-1",
            "type": "topAd"
        })
    })




 

    
    </script>















<script>
    //hata verdirebilir bulamaz ise
           document.getElementById('admin-panel-btn').addEventListener('click', function () {
            window.location.href= 'admin.php';
        });
</script>









</body>

</html>
