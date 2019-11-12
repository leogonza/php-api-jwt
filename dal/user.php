<?php 
	class User {

        private $id;
        private $email;
        private $pass;
        private $active;
        private $tokenSecret;

		private $tableName = 'users';
        private $dbConn;
        
        function setId($id) { $this->id = $id; }
        function setEmail($email) { $this->email = $email; }
        function setPass($pass) { $this->pass = $pass; }
        function getId() { return $this->id; }
        function getTokenSecret() { return $this->tokenSecret; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
        }

        public function get(){
            $stmt = $this->dbConn->prepare("SELECT id,email, password, active, token_secret FROM users WHERE id = :id");
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!is_array($user)){
                return false;
            }
            $this->active = $user['active'];
            $this->id = $user['id'];
            $this->email = $user['email'];
            $this->pass = $user['password'];
            $this->tokenSecret = $user['token_secret'];
            return true;
        }
        
        public function isValid(){
            $stmt = $this->dbConn->prepare("SELECT id,active FROM users WHERE email = :email AND password = :pass");
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":pass", $this->pass);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!is_array($user)){
                return false;
            }
            $this->active = $user['active'];
            $this->id = $user['id'];
            return true;
        }

        public function isActive(){
            return $this->active;
        }

		public function updateUserTokenSecret($tokenSecret) {
			$sql = "UPDATE $this->tableName SET token_secret = '$tokenSecret'";
			$sql .=	"WHERE id = :userId";
			$stmt = $this->dbConn->prepare($sql);
            $stmt->bindParam(':userId', $this->id);
            $this->tokenSecret = $tokenSecret;
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function getUserTokenSecret() {
			$sql = "SELECT token_secret  FROM $this->tableName";
			$sql .=	"WHERE id = :userId";
			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$tokenSecret = $stmt->execute();
			return $tokenSecret;
        }
        
    }
?>