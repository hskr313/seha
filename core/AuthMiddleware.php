<?php
class AuthMiddleware {
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    public static function getUser() {
        if (!self::isAuthenticated()) {
            return null;
        }
        $userRepository = new UserRepository();
        return $userRepository->findById($_SESSION['user_id']);
    }

    public static function hasRole($roleName) {
        $user = self::getUser();
        if (!$user) {
            return false;
        }
        $roleRepository = new RoleRepository();
        $role = $roleRepository->findById($user->role_id);
        return $role && $role->name === $roleName;
    }

    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: /seha/public/auth/login');
            exit();
        }
    }

    public static function requireRole($roleName) {
        if (!self::hasRole($roleName)) {
            header('Location: /seha/public/home');
            exit();
        }
    }
}
