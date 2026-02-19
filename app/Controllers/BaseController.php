<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LetterSettingModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected const POST_IMAGE_MAX_BYTES = 1048576; // 1MB

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'asset'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        if (session()->get('logged_in') && session()->get('user_id')) {
            if (! session()->get('link_token')) {
                session()->set('link_token', bin2hex(random_bytes(24)));
            }

            $userModel = new UserModel();
            $user      = $userModel->select('id,name,email,role')->find((int) session()->get('user_id'));

            if ($user) {
                session()->set([
                    'user_name'  => $user['name'],
                    'user_email' => $user['email'],
                    'user_role'  => strtolower((string) ($user['role'] ?? 'user')),
                ]);
            }
        }
    }

    protected function processPostImageUpload(string $fieldName, string $uploadSubdir, string $namePrefix): string|false|null
    {
        $file = $this->request->getFile($fieldName);
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (! $file->isValid() || $file->hasMoved()) {
            return false;
        }

        $allowedMime = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (! in_array((string) $file->getMimeType(), $allowedMime, true)) {
            return false;
        }

        $dir = FCPATH . trim($uploadSubdir, '/\\');
        if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
            return false;
        }

        if ((int) $file->getSize() <= self::POST_IMAGE_MAX_BYTES) {
            $ext = strtolower((string) $file->getExtension());
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $ext = 'jpg';
            }
            $newName = $namePrefix . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
            $file->move($dir, $newName, true);
            return trim($uploadSubdir, '/\\') . '/' . $newName;
        }

        if (! extension_loaded('gd')) {
            return false;
        }

        $newName = $namePrefix . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.jpg';
        $targetPath = $dir . DIRECTORY_SEPARATOR . $newName;
        $tmpPath = $file->getTempName();
        if (! $this->compressImageToJpegUnderLimit($tmpPath, $targetPath, self::POST_IMAGE_MAX_BYTES)) {
            return false;
        }
        if (is_file($targetPath) && filesize($targetPath) > self::POST_IMAGE_MAX_BYTES) {
            @unlink($targetPath);
            return false;
        }

        return trim($uploadSubdir, '/\\') . '/' . $newName;
    }

    protected function compressImageToJpegUnderLimit(string $sourcePath, string $targetPath, int $maxBytes): bool
    {
        if (! extension_loaded('gd')) {
            return false;
        }

        $info = @getimagesize($sourcePath);
        if (! is_array($info) || ! isset($info[2])) {
            return false;
        }

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if (! $src) {
            return false;
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);
        if ($srcW < 1 || $srcH < 1) {
            imagedestroy($src);
            return false;
        }

        $maxDim = 1800;
        $scale = min(1, $maxDim / max($srcW, $srcH));
        $dstW = max(1, (int) round($srcW * $scale));
        $dstH = max(1, (int) round($srcH * $scale));

        $canvas = imagecreatetruecolor($dstW, $dstH);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopyresampled($canvas, $src, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
        imagedestroy($src);

        $quality = 85;
        while ($quality >= 35) {
            @imagejpeg($canvas, $targetPath, $quality);
            if (is_file($targetPath) && filesize($targetPath) <= $maxBytes) {
                imagedestroy($canvas);
                return true;
            }
            $quality -= 5;
        }

        @imagejpeg($canvas, $targetPath, 35);
        imagedestroy($canvas);

        return is_file($targetPath);
    }

    protected function recaptchaIsEnabled(): bool
    {
        $setting = $this->recaptchaSettingFromDb();
        if ($setting !== null) {
            $enabled = (int) ($setting['recaptcha_enabled'] ?? 0) === 1;
            return $enabled
                && trim((string) ($setting['recaptcha_site_key'] ?? '')) !== ''
                && trim((string) ($setting['recaptcha_secret_key'] ?? '')) !== '';
        }

        $enabledEnv = strtolower(trim((string) env('recaptcha.enabled', 'false')));
        if (! in_array($enabledEnv, ['1', 'true', 'yes', 'on'], true)) {
            return false;
        }

        return trim((string) env('recaptcha.siteKey', '')) !== ''
            && trim((string) env('recaptcha.secretKey', '')) !== '';
    }

    protected function recaptchaSiteKey(): string
    {
        $setting = $this->recaptchaSettingFromDb();
        if ($setting !== null) {
            return trim((string) ($setting['recaptcha_site_key'] ?? ''));
        }

        return trim((string) env('recaptcha.siteKey', ''));
    }

    protected function verifyRecaptcha(?string &$errorMessage = null, string $expectedAction = 'submit'): bool
    {
        if (! $this->recaptchaIsEnabled()) {
            return true;
        }

        $token = trim((string) $this->request->getPost('g-recaptcha-response'));
        if ($token === '') {
            $errorMessage = 'Verifikasi reCAPTCHA wajib diisi.';
            return false;
        }

        $secretKey = '';
        $setting = $this->recaptchaSettingFromDb();
        if ($setting !== null) {
            $secretKey = trim((string) ($setting['recaptcha_secret_key'] ?? ''));
        }
        if ($secretKey === '') {
            $secretKey = trim((string) env('recaptcha.secretKey', ''));
        }
        if ($secretKey === '') {
            $errorMessage = 'Konfigurasi reCAPTCHA belum lengkap di server.';
            return false;
        }

        try {
            $client = service('curlrequest', [
                'timeout' => 10,
                'http_errors' => false,
            ]);
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret'   => $secretKey,
                    'response' => $token,
                    'remoteip' => (string) $this->request->getIPAddress(),
                ],
            ]);

            $body = (string) $response->getBody();
            $result = json_decode($body, true);
            if (! is_array($result) || ($result['success'] ?? false) !== true) {
                $errorCodes = [];
                if (is_array($result['error-codes'] ?? null)) {
                    $errorCodes = array_values(array_filter($result['error-codes'], static fn ($v): bool => is_string($v) && $v !== ''));
                }
                log_message('error', 'reCAPTCHA verify failed result: {result}', [
                    'result' => json_encode($result),
                ]);
                if (in_array('invalid-input-secret', $errorCodes, true)) {
                    $errorMessage = 'Secret key reCAPTCHA tidak valid. Periksa pengaturan admin.';
                    return false;
                }
                if (in_array('missing-input-secret', $errorCodes, true)) {
                    $errorMessage = 'Secret key reCAPTCHA belum diisi.';
                    return false;
                }
                if (in_array('invalid-input-response', $errorCodes, true) || in_array('missing-input-response', $errorCodes, true)) {
                    $errorMessage = 'Token reCAPTCHA tidak valid. Muat ulang halaman lalu coba lagi.';
                    return false;
                }
                $errorMessage = 'Verifikasi reCAPTCHA gagal. Coba lagi.';
                return false;
            }

            // reCAPTCHA v3 verification: score and action must match.
            $score = isset($result['score']) ? (float) $result['score'] : -1.0;
            if ($score < 0) {
                $errorMessage = 'Skor verifikasi reCAPTCHA tidak valid.';
                log_message('error', 'reCAPTCHA score missing/invalid: {result}', [
                    'result' => json_encode($result),
                ]);
                return false;
            }
            if ($score < $this->recaptchaMinScore()) {
                $errorMessage = 'Aktivitas terdeteksi mencurigakan. Coba lagi.';
                log_message('error', 'reCAPTCHA score too low ({score}) expected min {min}', [
                    'score' => (string) $score,
                    'min' => (string) $this->recaptchaMinScore(),
                ]);
                return false;
            }

            $hostname = trim((string) ($result['hostname'] ?? ''));
            if ($hostname !== '' && ! $this->isAllowedRecaptchaHostname($hostname)) {
                $errorMessage = 'Domain reCAPTCHA tidak cocok dengan konfigurasi key.';
                log_message('error', 'reCAPTCHA hostname mismatch: {hostname}', ['hostname' => $hostname]);
                return false;
            }

            $action = trim((string) ($result['action'] ?? ''));
            if ($expectedAction !== '' && $action !== $expectedAction) {
                $errorMessage = 'Aksi reCAPTCHA tidak sesuai. Muat ulang halaman lalu coba lagi.';
                log_message('error', 'reCAPTCHA action mismatch. expected={expected} actual={actual}', [
                    'expected' => $expectedAction,
                    'actual' => $action,
                ]);
                return false;
            }
        } catch (Throwable $e) {
            log_message('error', 'reCAPTCHA verify failed: {message}', ['message' => $e->getMessage()]);
            $errorMessage = 'Layanan verifikasi keamanan sedang bermasalah. Coba beberapa saat lagi.';
            return false;
        }

        return true;
    }

    private function recaptchaSettingFromDb(): ?array
    {
        try {
            $setting = (new LetterSettingModel())
                ->select('recaptcha_enabled,recaptcha_site_key,recaptcha_secret_key')
                ->first();

            if (! is_array($setting)) {
                return null;
            }

            $hasAnyValue = isset($setting['recaptcha_enabled'])
                || ! empty($setting['recaptcha_site_key'])
                || ! empty($setting['recaptcha_secret_key']);
            return $hasAnyValue ? $setting : null;
        } catch (Throwable $e) {
            log_message('error', 'reCAPTCHA setting load failed: {message}', ['message' => $e->getMessage()]);
            return null;
        }
    }

    private function recaptchaMinScore(): float
    {
        $raw = trim((string) env('recaptcha.scoreThreshold', '0.5'));
        $value = is_numeric($raw) ? (float) $raw : 0.5;
        if ($value < 0.1) {
            return 0.1;
        }
        if ($value > 0.9) {
            return 0.9;
        }

        return $value;
    }

    private function isAllowedRecaptchaHostname(string $hostname): bool
    {
        $host = strtolower(trim($hostname));
        if ($host === '') {
            return false;
        }

        $allowed = [
            strtolower((string) parse_url(base_url('/'), PHP_URL_HOST)),
            'localhost',
            '127.0.0.1',
        ];
        $allowed = array_values(array_unique(array_filter($allowed, static fn ($v): bool => is_string($v) && $v !== '')));

        return in_array($host, $allowed, true);
    }
}
