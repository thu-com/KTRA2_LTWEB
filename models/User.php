class User {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->query($sql, [$email])->fetch();
    }

    public function create($name, $email, $password) {
        $sql = "INSERT INTO users(name, email, password) VALUES (?, ?, ?)";
        return $this->db->query($sql, [$name, $email, $password]);
    }
}