<?php
class PreferenceController {

    public function set() {
        $key = $_POST['key'] ?? '';
        $val = $_POST['val'] ?? '';

        $allowed = [
            'pref_lang'     => ['EN', 'FR'],
            'pref_currency' => ['MAD', 'EUR'],
            'pref_theme'    => ['dark', 'light'],
        ];

        if (isset($allowed[$key]) && in_array($val, $allowed[$key])) {
            $_SESSION[$key] = $val;
        }

        // AJAX request: return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit;
        }

        // Regular form: redirect back
        $back = ltrim($_POST['redirect'] ?? 'dashboard', '/');
        redirect($back);
    }
}
