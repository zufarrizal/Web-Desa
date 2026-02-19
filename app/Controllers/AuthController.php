<?php

namespace App\Controllers;

use App\Models\PasswordResetModel;
use App\Models\UserModel;
use Throwable;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'recaptchaEnabled' => $this->recaptchaIsEnabled(),
            'recaptchaSiteKey' => $this->recaptchaSiteKey(),
        ]);
    }

    public function attemptLogin()
    {
        $recaptchaError = null;
        if (! $this->verifyRecaptcha($recaptchaError, 'login')) {
            return redirect()->back()->withInput()->with('error', (string) $recaptchaError);
        }

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');
        $ip       = (string) $this->request->getIPAddress();
        $ipKey    = preg_replace('/[^A-Za-z0-9_-]/', '_', $ip) ?? 'unknown_ip';
        $emailKey = sha1(strtolower(trim($email)));

        $throttler = service('throttler');
        if (! $throttler->check('login_ip_' . $ipKey, 30, MINUTE)) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan login dari IP ini. Coba lagi 1 menit lagi.');
        }
        if (! $throttler->check('login_cred_' . $ipKey . '_' . $emailKey, 5, 300)) {
            return redirect()->back()->withInput()->with('error', 'Percobaan login terlalu sering. Coba lagi dalam 5 menit.');
        }

        try {
            $userModel = new UserModel();
            $user      = $userModel->where('email', $email)->first();
        } catch (Throwable $e) {
            log_message('error', 'Login DB error: {message}', ['message' => $e->getMessage()]);

            return redirect()->back()->withInput()->with(
                'error',
                'Koneksi database gagal. Pastikan MySQL aktif dan konfigurasi database sudah benar.'
            );
        }

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        $linkToken = bin2hex(random_bytes(24));
        session()->regenerate();
        session()->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_role'  => strtolower((string) ($user['role'] ?? 'user')),
            'link_token' => $linkToken,
            'logged_in'  => true,
        ]);

        $userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/dashboard?_lt=' . $linkToken);
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register', [
            'recaptchaEnabled' => $this->recaptchaIsEnabled(),
            'recaptchaSiteKey' => $this->recaptchaSiteKey(),
        ]);
    }

    public function attemptRegister()
    {
        $recaptchaError = null;
        if (! $this->verifyRecaptcha($recaptchaError, 'register')) {
            return redirect()->back()->withInput()->with('error', (string) $recaptchaError);
        }

        $rules = [
            'name'             => 'required|min_length[3]|max_length[120]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]|max_length[100]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ip = (string) $this->request->getIPAddress();
        $ipKey = preg_replace('/[^A-Za-z0-9_-]/', '_', $ip) ?? 'unknown_ip';
        $throttler = service('throttler');
        if (! $throttler->check('register_ip_' . $ipKey, 10, 600)) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan registrasi. Coba lagi beberapa saat.');
        }

        try {
            $userModel = new UserModel();
            $userModel->insert([
                'name'                => (string) $this->request->getPost('name'),
                'email'               => (string) $this->request->getPost('email'),
                'password'            => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
                'role'                => 'user',
                'registration_source' => 'register',
            ]);
        } catch (Throwable $e) {
            log_message('error', 'Register DB error: {message}', ['message' => $e->getMessage()]);

            return redirect()->back()->withInput()->with(
                'error',
                'Registrasi gagal karena masalah sistem. Silakan coba lagi.'
            );
        }

        return redirect()->to('/login')->with('success', 'Registrasi berhasil. Silakan login sebagai warga.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }

    public function forgotPassword()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/forgot_password');
    }

    public function sendResetLink()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = (string) $this->request->getPost('email');
        $ip = (string) $this->request->getIPAddress();
        $ipKey = preg_replace('/[^A-Za-z0-9_-]/', '_', $ip) ?? 'unknown_ip';
        $throttler = service('throttler');
        if (! $throttler->check('forgot_ip_' . $ipKey, 10, 600)) {
            return redirect()->back()->withInput()->with('error', 'Permintaan reset terlalu sering. Coba lagi beberapa saat.');
        }

        try {
            $userModel  = new UserModel();
            $resetModel = new PasswordResetModel();
            $user       = $userModel->where('email', $email)->first();

            // Always return generic success message, even if email is not found.
            if (! $user) {
                return redirect()->to('/forgot-password')->with('success', 'Jika email terdaftar, link reset password akan dikirim.');
            }

            $token     = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $expiresAt = date('Y-m-d H:i:s', time() + 3600);

            $resetModel->where('email', $email)->delete();
            $resetModel->insert([
                'user_id'    => $user['id'],
                'email'      => $email,
                'token_hash' => $tokenHash,
                'expires_at' => $expiresAt,
                'used_at'    => null,
            ]);

            $resetLink = site_url('reset-password/' . $token);

            $emailService = service('email');
            $emailService->setTo($email);
            $emailService->setSubject('Reset Password Portal Desa');
            $emailService->setMessage(
                "Halo {$user['name']},\n\n" .
                "Kami menerima permintaan reset password akun Anda.\n" .
                "Klik link berikut untuk membuat password baru:\n{$resetLink}\n\n" .
                "Link berlaku selama 60 menit.\n" .
                "Jika Anda tidak meminta reset password, abaikan email ini."
            );

            if (! $emailService->send()) {
                log_message('error', 'Reset email failed: {debug}', ['debug' => (string) $emailService->printDebugger(['headers'])]);
                return redirect()->back()->withInput()->with('error', 'Gagal mengirim email reset. Cek konfigurasi SMTP.');
            }
        } catch (Throwable $e) {
            log_message('error', 'Forgot password error: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }

        return redirect()->to('/forgot-password')->with('success', 'Jika email terdaftar, link reset password akan dikirim.');
    }

    public function resetPassword(string $token)
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        $record = $this->validResetRecord($token);
        if (! $record) {
            return redirect()->to('/forgot-password')->with('error', 'Token reset tidak valid atau sudah kadaluarsa.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function attemptResetPassword(string $token)
    {
        $record = $this->validResetRecord($token);
        if (! $record) {
            return redirect()->to('/forgot-password')->with('error', 'Token reset tidak valid atau sudah kadaluarsa.');
        }

        $rules = [
            'password'         => 'required|min_length[6]|max_length[100]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $userModel  = new UserModel();
            $resetModel = new PasswordResetModel();

            $userModel->update((int) $record['user_id'], [
                'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            ]);

            $resetModel->update((int) $record['id'], [
                'used_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Throwable $e) {
            log_message('error', 'Reset password error: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah password. Silakan coba lagi.');
        }

        return redirect()->to('/login')->with('success', 'Password berhasil diubah. Silakan login.');
    }

    private function validResetRecord(string $token): ?array
    {
        $tokenHash  = hash('sha256', $token);
        $resetModel = new PasswordResetModel();

        $record = $resetModel
            ->where('token_hash', $tokenHash)
            ->where('used_at', null)
            ->first();

        if (! $record) {
            return null;
        }

        if (strtotime((string) $record['expires_at']) < time()) {
            return null;
        }

        return $record;
    }
}
