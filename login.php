<?php
session_start(); // Oturum başlat

// Kullanıcı zaten giriş yaptıysa direkt telegram.php'ye yönlendir
if (isset($_SESSION['user_id'])) {
    header("Location: telegram.php");
      // Yönlendirmeden sonra scriptin çalışmasını durdurmak için
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <title>  App Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container text-center mt-5">
    <h1>Welcome to RRFF Web App</h1>
    <p>Please log in to continue click</p>
    <button id="login-btn" class="btn btn-primary">Login </button>
</div>
<script>
    document.getElementById('login-btn').addEventListener('click', function() {
        // Doğrudan telegram.php sayfasına yönlendirme
        window.location.href = 'telegram.php';
    });
</script>
    <script>
    // Telegram Web Apps API'sini kullanarak kullanıcı bilgilerini al
    window.Telegram.WebApp.ready();

    const telegramUser = window.Telegram.WebApp.initDataUnsafe.user;
    const userId = telegramUser.id;
    const userName = telegramUser.username || 'Bilinmiyor';
    const userFirstName = telegramUser.first_name || 'Bilinmiyor';
    const userLastName = telegramUser.last_name || 'Bilinmiyor';
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
        sendUsernameToServer(userName);
    // Kullanıcı bilgilerini PHP ile sunucuya gönder
    fetch('save_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: userId,
            username: userName,
            first_name: userFirstName,
            last_name: userLastName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'exists' || data.status === 'success') {
            // Başarılı işlemden sonra telegram.php sayfasına yönlendir
            window.location.href = 'telegram.php';
            
        } else {
            alert('Bir hata oluştu: ' + data.message);
        }
        console.log('Sunucu Yanıtı:', data);
    })
    .catch(error => {
        console.error('Hata:', error);
    });
    </script>

</body>
</html>
