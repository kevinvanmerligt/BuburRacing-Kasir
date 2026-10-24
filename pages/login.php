<?php
session_start();
require '../connection/koneksi.php';

$error = '';
$trigger_alert = false;
$alert_message = '';
$redirect_to_reg = false;

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $login_success = false;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $login_success = true;
            } else if ($password === $row['password']) {
                $login_success = true;
            }
        }

        if ($login_success) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['username'] = $username;
            $_SESSION['id_pengguna'] = $row['id']; 

            header("Location: produk.php"); 
            exit();
        } else {
            $_SESSION['login_attempts']++;

            if ($_SESSION['login_attempts'] >= 3) {
                $alert_message = "Anda telah salah memasukkan password 3 kali. Silakan daftar akun terlebih dahulu!";
                $redirect_to_reg = true;
                $_SESSION['login_attempts'] = 0;
            } else {
                $sisa_percobaan = 3 - $_SESSION['login_attempts'];
                $alert_message = "Username atau password salah! Coba ulang lagi. (Sisa percobaan: $sisa_percobaan)";
                $trigger_alert = true;
                $error = "Username atau password salah!";
            }
        }
        $stmt->close();
    } else {
        $error = "Username dan password tidak boleh kosong!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - Kasir Bubur Racing</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Squada+One&display=swap" rel="stylesheet">
    <style>
        .font-heading { font-family: 'Playfair Display', serif; }
        .font-logo { font-family: 'Squada One', sans-serif; }
    </style>
</head>
<body class="m-0 p-0 bg-white">
    <div class="w-full min-h-screen flex flex-col md:flex-row overflow-hidden">
        <div class="w-full md:w-1/2 bg-[#3b173c] relative flex items-end p-8 md:p-12 min-h-[25vh] md:min-h-screen">
            <div class="text-white font-logo tracking-widest text-4xl md:text-[2.5rem] leading-[1.1]">
                <div>KASIR</div>
                <div>BUBUR RACING</div>
            </div>
        </div>
        <div class="w-full md:w-1/2 bg-white flex items-center justify-center p-6 md:p-12 flex-1">
            <div class="bg-white rounded-3xl shadow-[0_5px_40px_rgba(0,0,0,0.15)] w-full max-w-[400px] p-8 md:p-10 flex flex-col items-center">
                <h2 class="font-heading text-4xl mb-8 text-black">Login</h2>

                <?php if($error): ?>
                    <div class="w-full bg-red-100 text-red-600 text-sm text-center py-3 rounded-lg mb-6">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="w-full flex flex-col gap-5">
                    <div>
                        <input type="text" name="username" placeholder="Username" autocomplete="off" required
                            class="w-full bg-[#e8e8e8] text-gray-800 text-base placeholder-gray-500 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3b173c] transition-all">
                    </div>
                    <div>
                        <input type="password" name="password" placeholder="Password" autocomplete="new-password" required
                            class="w-full bg-[#e8e8e8] text-gray-800 text-base placeholder-gray-500 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3b173c] transition-all">
                    </div>
                    <div class="flex justify-between w-full text-sm text-gray-700 mt-2 px-1">
                        <a href="reg.php" class="hover:text-[#3b173c] hover:font-bold transition-all">Daftar Akun</a>
                        <a href="forget.php" class="hover:text-[#3b173c] hover:font-bold transition-all">Lupa Password?</a>
                    </div>
                    <div class="mt-8 flex justify-center w-full">
                        <button type="submit" 
                            class="w-full bg-[#3b173c] text-white text-base font-semibold px-8 py-3 rounded-xl hover:bg-[#572458] transition duration-300 shadow-md">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($redirect_to_reg): ?>
        <script>
            alert("<?= $alert_message ?>");
            window.location.href = 'reg.php';
        </script>
    <?php elseif ($trigger_alert): ?>
        <script>
            alert("<?= $alert_message ?>");
        </script>
    <?php endif; ?>
</body>
</html>