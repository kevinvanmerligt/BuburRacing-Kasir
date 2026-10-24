<?php
session_start();
require '../connection/koneksi.php';

$error = '';
$success_msg = '';
$show_otp_alert = false;
$reset_code = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if (isset($_POST['send_otp'])) {
        if (!empty($email)) {
            $stmt = $koneksi->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $reset_code = rand(100000, 999999);
                $_SESSION['reset_code'] = $reset_code;
                $_SESSION['reset_email'] = $email;
                $show_otp_alert = true;
            } else {
                $error = "Email tidak terdaftar!";
            }
            $stmt->close();
        } else {
            $error = "Masukkan email terlebih dahulu untuk mengirim OTP!";
        }
    } elseif (isset($_POST['reset_password'])) {
        $otp = trim($_POST['otp'] ?? '');
        $new_password = trim($_POST['new_password'] ?? '');

        if (empty($email) || empty($otp) || empty($new_password)) {
            $error = "Semua kolom (Email, OTP, Password Baru) harus diisi!";
        } else {
            if (isset($_SESSION['reset_code']) && isset($_SESSION['reset_email'])) {
                if ($email === $_SESSION['reset_email'] && $otp == $_SESSION['reset_code']) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    $stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE email = ?");
                    $stmt->bind_param("ss", $hashed_password, $email);
                    
                    if ($stmt->execute()) {
                        $success_msg = "Password berhasil diubah! Silakan login.";
                        unset($_SESSION['reset_code']);
                        unset($_SESSION['reset_email']);
                    } else {
                        $error = "Gagal mengubah password!";
                    }
                    $stmt->close();
                } else {
                    $error = "Kode OTP salah atau email tidak sesuai!";
                }
            } else {
                $error = "Silakan kirim kode OTP terlebih dahulu!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Lupa Password - Kasir Bubur Racing</title>
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
                <h2 class="font-heading text-4xl mb-8 text-black text-center">Lupa Password</h2>
                
                <?php if($error): ?>
                    <div class="w-full bg-red-100 text-red-600 text-sm text-center py-3 rounded-lg mb-6">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="w-full flex flex-col gap-5">
                    <div>
                        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Email Anda" autocomplete="off" required
                            class="w-full bg-[#e8e8e8] text-gray-800 text-base placeholder-gray-500 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3b173c] transition-all">
                    </div>
                    
                    <div class="flex gap-3">
                        <input type="text" name="otp" placeholder="Kode OTP" autocomplete="off"
                            class="w-full bg-[#e8e8e8] text-gray-800 text-base placeholder-gray-500 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3b173c] transition-all">
                        <button type="submit" name="send_otp"
                            class="bg-[#3b173c] text-white text-base font-semibold px-6 py-3 rounded-xl hover:bg-[#572458] transition duration-300 shadow-md whitespace-nowrap">
                            Kirim
                        </button>
                    </div>

                    <div>
                        <input type="password" name="new_password" placeholder="Password Baru" autocomplete="new-password"
                            class="w-full bg-[#e8e8e8] text-gray-800 text-base placeholder-gray-500 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3b173c] transition-all">
                    </div>

                    <div class="flex justify-center w-full text-sm text-gray-700 mt-2 px-1">
                        <a href="login.php" class="hover:text-[#3b173c] hover:font-bold transition-all">Kembali ke Login</a>
                    </div>

                    <div class="mt-4 flex justify-center w-full">
                        <button type="submit" name="reset_password"
                            class="w-full bg-[#3b173c] text-white text-base font-semibold px-8 py-3 rounded-xl hover:bg-[#572458] transition duration-300 shadow-md">
                            Simpan Password Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($show_otp_alert): ?>
        <script>
            alert("Simulasi Email!\n\nKode OTP Anda adalah: <?= $reset_code ?>\n\nNantinya ini akan dikirim via PHPMailer.");
        </script>
    <?php elseif ($success_msg): ?>
        <script>
            alert("<?= $success_msg ?>");
            window.location.href = 'login.php';
        </script>
    <?php endif; ?>
</body>
</html>