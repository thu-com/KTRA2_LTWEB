class AuthController extends Controller {

    public function login() {
        $this->view("auth/login");
    }

    public function doLogin() {
        $userModel = $this->model("User");

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $userModel->findByEmail($email);

        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            header("Location: /");
        } else {
            echo "Sai tài khoản hoặc mật khẩu";
        }
    }

    public function register() {
        $this->view("auth/register");
    }

    public function doRegister() {
        $userModel = $this->model("User");

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $userModel->create($name, $email, $password);

        header("Location: /auth/login");
    }

    public function logout() {
        session_destroy();
        header("Location: /auth/login");
    }
}