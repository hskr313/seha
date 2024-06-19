<?php
class UserController extends BaseController {
    public function profile() {
        AuthMiddleware::requireAuth();
        $user = AuthMiddleware::getUser();
        $this->view('user/profile', ['title' => 'My Profile', 'user' => $user]);
    }

    public function updateProfile() {
        AuthMiddleware::requireAuth();
        $userRepository = new UserRepository();
        $user = $userRepository->findById($_SESSION['user_id']);

        $user->username = $_POST['username'];
        $user->email = $_POST['email'];

        if ($_POST['time_credit']) {
            throw new Error();
        }

        $userRepository->update($user->id, [
            'username' => $user->username,
            'email' => $user->email,
        ]);

        header('Location: /seha/public/user/profile');
        exit();
    }
}
