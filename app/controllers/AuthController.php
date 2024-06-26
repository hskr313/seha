<?php
class AuthController extends BaseController {
    private $userRepository;
    private $roleRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
    }

    public function login() {
        if (AuthMiddleware::isAuthenticated()){
            header('Location: /seha/public');
            exit();
        }

        $this->view('auth/login', ['title' => 'Login'], 'auth');
    }

    public function loginPost() {
        session_start();
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->findByEmail($email);

        echo $user->email;

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            header('Location: /seha/public/');
        } else {
            header('Location: /seha/public/auth/login?error=invalid_credentials');
        }
        exit();
    }

    public function register() {
        $this->view('auth/register', ['title' => 'Register'], 'auth');
    }

    public function registerPost() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $user = new UserEntity(
            null,
            $name,
            $password,
            $email,
        );

        $this->userRepository->create($user);

        header('Location: /seha/public/auth/login');
        exit();
    }

    public function logout() {
        session_destroy();
        header('Location: /seha/public/auth/login');
        exit();
    }
}

