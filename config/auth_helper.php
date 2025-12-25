<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a specific role is logged in
 * @param string $role 'admin', 'agent', 'user'
 * @return bool
 */
function isLoggedIn($role = 'user') {
    switch ($role) {
        case 'admin':
            return !empty($_SESSION['sdtravels_manager']);
        case 'agent':
            return !empty($_SESSION['sdtravels_agent']);
        case 'user':
            return !empty($_SESSION['sdtravels_user']);
        default:
            return false;
    }
}

/**
 * Get current logged in user data/id
 * @param string $role
 * @return mixed
 */
function auth($role = 'user') {
    if (!isLoggedIn($role)) return null;
    
    switch ($role) {
        case 'admin':
            return $_SESSION['sdtravels_manager'];
        case 'agent':
            return $_SESSION['sdtravels_agent'];
        case 'user':
            return $_SESSION['sdtravels_user']; // This appears to be just the UUID string in existing code
        default:
            return null;
    }
}

/**
 * Login a user/agent/admin
 * @param string $role
 * @param mixed $data
 */
function loginUser($role, $data) {
    switch ($role) {
        case 'admin':
            $_SESSION['sdtravels_manager'] = $data;
            break;
        case 'agent':
            $_SESSION['sdtravels_agent'] = $data;
            break;
        case 'user':
            $_SESSION['sdtravels_user'] = $data;
            break;
    }
}

/**
 * Logout
 * @param string $role
 */
function logout($role) {
    switch ($role) {
        case 'admin':
            unset($_SESSION['sdtravels_manager']);
            break;
        case 'agent':
            unset($_SESSION['sdtravels_agent']);
            break;
        case 'user':
            unset($_SESSION['sdtravels_user']);
            break;
    }
}
?>
