<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

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
    protected $helpers = ['url', 'form'];

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
}
