<?php
session_start();
require '../connection/koneksi.php';

$error = '';
$success = false;
$alert_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($email) && !empty($password)) {
        $stmt_check = $koneksi->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error = "Username atau Email sudah terdaftar!";
        } else {
            $stmt_insert = $koneksi->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $email,$password);

            if ($stmt_insert->execute()) {
                $success = true;
                $alert_message = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan sistem, silakan coba lagi.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    } else {
        $error = "Semua kolom harus diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Daftar Akun - Kasir Bubur Racing</title>
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
                <h2 class="font-heading text-4xl mb-8 text-black">Daftar Akun</h2>
                
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
                        <input type="email" name="email" placeholder="Email" autocomplete="off" required
                            class="w-full bg-[#e8e8e8] text-gray-800 text-base placeholder-gray-500 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3b173c] transition-all">
                    </div>
                    <div>
                        <input type="password" name="password" placeholder="Password" autocomplete="new-password" required
                            class="w-full bg-[#e8e8e8] text-gray-800 text-base placeholder-gray-500 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-[#3b173c] transition-all">
                    </div>
                    <div class="flex justify-center w-full text-sm text-gray-700 mt-2 px-1">
                        <a href="login.php" class="hover:text-[#3b173c] hover:font-bold transition-all">Sudah punya akun? Login di sini</a>
                    </div>
                    <div class="mt-8 flex justify-center w-full">
                        <button type="submit" 
                            class="w-full bg-[#3b173c] text-white text-base font-semibold px-8 py-3 rounded-xl hover:bg-[#572458] transition duration-300 shadow-md">
                            Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($success): ?>
        <script>
            alert("<?= $alert_message ?>");
            window.location.href = 'login.php';
        </script>
    <?php endif; ?>
</body>
</html>