<?php
session_start();

class SecurityHelper {
    // Generate a secure key for AES-256-CBC and store it safely. In a real app, this should be in .env
    private static $encryption_key = "twiis_secure_encryption_key_2026"; // 32 chars minimum
    private static $cipher_method = "aes-256-cbc";

    // 1. Encryption Methods (OWASP A02: Cryptographic Failures)
    public static function encrypt($data) {
        if (!$data) return $data;
        $key = hash('sha256', self::$encryption_key, true);
        $iv_length = openssl_cipher_iv_length(self::$cipher_method);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($data, self::$cipher_method, $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($data) {
        if (!$data || !self::isBase64($data)) return $data;
        $key = hash('sha256', self::$encryption_key, true);
        $data = base64_decode($data);
        $iv_length = openssl_cipher_iv_length(self::$cipher_method);
        if (strlen($data) <= $iv_length) return $data; // Not encrypted
        $iv = substr($data, 0, $iv_length);
        $encrypted = substr($data, $iv_length);
        $decrypted = openssl_decrypt($encrypted, self::$cipher_method, $key, 0, $iv);
        return $decrypted !== false ? $decrypted : $data;
    }

    private static function isBase64($string) {
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;
        $decoded = base64_decode($string, true);
        if ($decoded === false) return false;
        if (base64_encode($decoded) != $string) return false;
        return true;
    }

    // 2. CSRF Token Generation & Validation (OWASP A08: Software and Data Integrity Failures)
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken($token) {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    // 3. Rate Limiting (OWASP A04: Insecure Design)
    public static function checkRateLimit($ip, $limit = 5, $windowSeconds = 60) {
        $file = __DIR__ . '/../data/rate_limits.json';
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $now = time();

        // Cleanup old entries
        foreach ($data as $stored_ip => $requests) {
            $data[$stored_ip] = array_filter($requests, function($timestamp) use ($now, $windowSeconds) {
                return ($now - $timestamp) < $windowSeconds;
            });
            if (empty($data[$stored_ip])) {
                unset($data[$stored_ip]);
            }
        }

        if (!isset($data[$ip])) {
            $data[$ip] = [];
        }

        if (count($data[$ip]) >= $limit) {
            file_put_contents($file, json_encode($data));
            return false; // Rate limit exceeded
        }

        $data[$ip][] = $now;
        file_put_contents($file, json_encode($data));
        return true;
    }
}
?>
