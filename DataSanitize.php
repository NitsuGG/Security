<?php

namespace Core\Security;

use Exception;

class DataSanitize
{
    
    /**
     * var_sanitize
     * Sanitize data by removing all html characters and EOL
     * 
     * @param  string $data
     * @return string
     */
    public function sanitize_var(string $data): string
    {
        $data = htmlspecialchars($data);
        $data = str_replace(["\r", "\n", PHP_EOL], "", $data);

        return $data;
    }
    
    /**
     * sanitize_array
     * Sanitize array by removing all html characters and EOL
     * @param  array $data
     * @return array
     */
    public function sanitize_array(array $data): array
    {
        $tab = [];
        foreach ($data as $key => $value) {
            if (!is_array($value)) {

                $value = htmlspecialchars($value);
                $value = str_replace(["\r", "\n", PHP_EOL], "", $value); 
                $tab[$key] = $value;

            }else {

                $value = $this->sanitize_array($value);
                $tab[$key] = $value;
            }
        }
        return $tab;
    }
    
    /**
     * generate_csrf_token
     * Generate a CSRF token into a session
     * @param  int | float $randomInt1
     * @param  int | float $randomInt2
     * @return void
     */
    public function generate_csrf_token(int | float $randomInt1, int | float $randomInt2): void
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = md5(time() * rand($randomInt1, $randomInt2));
        }
    }
    
    /**
     * generate_csrf_input
     * Generate hidden input with csrf_token stored from session.
     * @throws Exception
     * @return void
     */
    public function generate_csrf_input()
    {
        if (!isset($_SESSION['csrf_token'])) {
            throw new Exception("Their is no csrf token stored int session. Can't create input.");
        }
        return '<input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">';
    }    

    /**
     * csrf_verification
     *
     * @param  string $csrf_token
     * @param  string $session_csrf_token
     * @throws Exception
     * @return void
     */
    public function csrf_verification(String $session_csrf_token): void
    {
        if (isset($session_csrf_token) && isset($_GET['csrf_token'])) {
            if ($_GET['csrf_token'] !== $session_csrf_token) {
                throw new Exception("Invalid csrf token");
            }
        }
        if (isset($session_csrf_token) && isset($_POST['csrf_token'])) {
            if ($_POST['csrf_token'] !== $session_csrf_token) {
                throw new Exception("Invalid csrf token");
            }
        }
        
    }

}