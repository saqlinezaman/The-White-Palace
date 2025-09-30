<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db_config.php";

class User
{
    private $connection;
    private $baseUrl;

    public function __construct($pdo = null)
    {
        $database = new Database();
        $db = $database->db_connection();
        $this->connection = $db;

        $this->baseUrl = defined("BASE_URL") ? BASE_URL : $this->guessBaseUrl();
    }

    function guessBaseUrl()
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . '/thewhitepalace';
    }

    // helper method
    public function redirect($url)
    {
        header("Location: " . $url);
        exit;
    }

    public function is_logged_in()
    {
        return !empty($_SESSION["user_id"]);
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        return true;
    }

    public function get_user_by_id($id)
    {
        $statement = $this->connection->prepare("SELECT id, username, email, verified FROM users WHERE id = ? LIMIT 1");
        $statement->execute([$id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // auth core register
    public function register($username, $email, $password)
    {
        $statement = $this->connection->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $statement->execute([$email]);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        if ($statement->fetch()) {
            throw new Exception("This email is already registered!");
        }

        $token = bin2hex(random_bytes(16));
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $insert_query = $this->connection->prepare("INSERT INTO users (username, email, password, token, verified) VALUES (?,?,?,?,0)");
        $insert_query->execute([$username, $email, $hash, $token]);

        $verifyLink = $this->baseUrl . "/auth/verify.php?token=" . urlencode($token) . "&email=" . urlencode($email);

        $msg = '
        <div style="font-family: Arial; font-size: 14px; line-height: 1.6; color: #333;">
            <h2 style="margin: 0 0 12px;">Verify your email</h2>
            <p>Hi ' . htmlspecialchars($username) . ',</p>
            <p>Please click the button below to verify your account:</p>
            <p style="margin: 16px 0;">
                <a href="' . htmlspecialchars($verifyLink) . '" target="_blank" style="background: #007bff; color: white; text-decoration: none; padding: 10px 18px; border-radius: 6px; display: inline-block;">
                   Verify my account
                </a>
            </p>
        </div>
        ';
        $this->sendMail($email, 'Verify your email', $msg);
        return true;
    }

    // login
    public function login($email, $password)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $statement->execute([$email]);

        $u = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$u) {
            throw new Exception("Invalid Credentials");
        }
        if (!password_verify($password, $u["password"])) {
            throw new Exception("Invalid Credentials");
        }
        if ((int) $u['verified'] !== 1) {
            throw new Exception("Your email is not verified please verify your email");
        }

        $_SESSION['user_id'] = $u['id'];
        $_SESSION['user_email'] = $u['email'];
        $_SESSION['user_name'] = $u['username'];

        header('Location:' . $this->baseUrl . '/Frontend');
        exit;
    }

    // verify
    public function verify($email, $token)
    {
        $statement = $this->connection->prepare('SELECT id, token, verified FROM users WHERE email = ? LIMIT 1');
        $statement->execute([$email]);
        $u = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$u) {
            throw new Exception("Account not found");
        }
        if ((int) $u['verified'] === 1) {
            return true;
        }
        if (!hash_equals($u['token'] ?? '', $token ?? '')) {
            throw new Exception('Invalid Verification token');
        }

        $update = $this->connection->prepare('UPDATE users SET verified = 1, token = null WHERE id = ?');
        $update->execute([$u['id']]);
        return true;
    }

    // request reset
    public function requestPasswordReset($email)
    {
        $statement = $this->connection->prepare('SELECT id, username FROM users WHERE email = ? LIMIT 1');
        $statement->execute([$email]);
        $u = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$u) return true;

        $token = bin2hex(random_bytes(16));
        $expire = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

        $up = $this->connection->prepare('UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?');
        $up->execute([$token, $expire, $u['id']]);

        $reset_link = $this->baseUrl . "/auth/reset.php?token=" . urlencode($token) . "&email=" . urlencode($email);

        $message = '
        <div style="font-family: Arial; font-size: 14px; line-height: 1.6; color: #333;">
            <h2 style="margin: 0 0 12px;">Password Reset</h2>
            <p>Hi ' . htmlspecialchars($u['username']) . ',</p>
            <p>Click the button below to set a new password:</p>
            <p style="margin: 16px 0;">
                <a href="' . htmlspecialchars($reset_link) . '" target="_blank" style="background: #007bff; color: white; text-decoration: none; padding: 10px 18px; border-radius: 6px; display: inline-block;">
                    Reset My Password
                </a>
            </p>
        </div>
        ';

        $this->sendMail($email, "Reset your password", $message);
        return true;
    }

    // reset password
    public function resetPassword($email, $token, $new_password)
    {
        $statement = $this->connection->prepare("SELECT id, reset_token, reset_expires FROM users WHERE email = ? LIMIT 1");
        $statement->execute([$email]);
        $u = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$u) throw new Exception("Account not found");

        if (empty($u['reset_token']) || !hash_equals($u['reset_token'], $token ?? '')) {
            throw new Exception("Invalid or expired token");
        }

        if (!empty($u['reset_expires'])) {
            $now = new DateTime();
            $exp = new DateTime($u['reset_expires']);
            if ($now > $exp) {
                throw new Exception("Reset token expired, try again");
            }
        }

        $hash = password_hash($new_password, PASSWORD_DEFAULT);

        $update = $this->connection->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $update->execute([$hash, $u['id']]);

        return true;
    }

    // send invoice (email)
    public function sendInvoice($email, $booking, $room)
    {
        $due_amount = $booking['total_price'] - $booking['advance_amount'];

        $extraServices = json_decode($booking['extra_services'], true) ?? [];
        $servicesList = [];
        if (!empty($extraServices)) {
            $placeholders = implode(',', array_fill(0, count($extraServices), '?'));
            $stmtServices = $this->connection->prepare("SELECT title, price FROM services WHERE id IN ($placeholders)");
            $stmtServices->execute($extraServices);
            $servicesData = $stmtServices->fetchAll(PDO::FETCH_ASSOC);
            foreach ($servicesData as $s) {
                $servicesList[] = $s['title'] . ' (Taka: ' . number_format($s['price'], 2, '.', ',') . ')';
            }
        }
        $extraServicesStr = implode(', ', $servicesList);

        $subject = "Booking Invoice - #" . htmlspecialchars($booking['id']);
        $message = '
        <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
            <h1>The White Palace</h1>
            <h5 style="margin-bottom: 20px;">Your comfort is our priority.</h5>
            <h2>Booking Invoice</h2>
            <p>Hi ' . htmlspecialchars($booking['user_name']) . ',</p>
            <p>Thank you for booking with us. Here are your booking details:</p>
            <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin: 12px 0; width: 100%;">
                <tr><td><strong>Date</strong></td><td>' . htmlspecialchars($booking['created_at']) . '</td></tr>
                <tr><td><strong>Booking ID</strong></td><td>' . htmlspecialchars($booking['id']) . '</td></tr>
                <tr><td><strong>Room</strong></td><td>' . htmlspecialchars($room['name']) . '</td></tr>
                <tr><td><strong>Name</strong></td><td>' . htmlspecialchars($booking['user_name']) . '</td></tr>
                <tr><td><strong>Phone</strong></td><td>' . htmlspecialchars($booking['user_phone']) . '</td></tr>
                <tr><td><strong>Check-in</strong></td><td>' . htmlspecialchars($booking['check_in']) . '</td></tr>
                <tr><td><strong>Check-out</strong></td><td>' . htmlspecialchars($booking['check_out']) . '</td></tr>
                <tr><td><strong>Extra Services</strong></td><td>' . htmlspecialchars($extraServicesStr ?? 'None') . '</td></tr>
                <tr><td><strong>Total Nights</strong></td><td>' . htmlspecialchars($booking['nights'] ?? '') . '</td></tr>
                <tr><td><strong>Total Price</strong></td><td>Taka: ' . number_format((float)$booking['total_price'], 2, '.', ',') . '</td></tr>
                <tr><td><strong>Advance Paid</strong></td><td>Taka: ' . number_format((float)$booking['advance_amount'], 2, '.', ',') . '</td></tr>
                <tr><td><strong>Due Amount</strong></td><td>Taka: ' . number_format((float)htmlspecialchars($due_amount), 2, '.', ',') . '</td></tr>
                <tr><td><strong>Payment Status</strong></td><td>' . htmlspecialchars($booking['payment_status']) . '</td></tr>
            </table>
            <p>We look forward to hosting you!</p>
            <hr>
            <p style="font-size:12px; color:#777;">This is an automated invoice email from The White Palace.</p>
        </div>
        ';

        return $this->sendMail($email, $subject, $message);
    }
    // sendMail function (changed to public)
    public function sendMail($email, $subject, $message)
    {
        require_once __DIR__ . '/mailer/PHPMailer.php';
        require_once __DIR__ . '/mailer/SMTP.php';
        require_once __DIR__ . '/mailer/Exception.php';


        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'szmoaj100@gmail.com';
        $mail->Password = 'pejgwhvsrltwfdfe';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('szmoaj100@gmail.com', 'The White Palace');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        if (!$mail->send()) {
            $_SESSION['mailError'] = $mail->ErrorInfo ?? 'mail send error';
            return false;
        }
        return true;
    }
}
?>