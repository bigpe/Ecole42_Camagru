<?php
    $db = new database();
    class database
    {
        private $db;

        public function __construct()
        {
            $DB_DSN = "mysql:dbname=YOUR_DB_NAME;host=YOUR_HOST;port=YOUR_PORT";
            $DB_USER = "YOUR_USER";
            $DB_PASSWORD = "YOUR_PASS";
            $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
        public function db_read($query)
        {
            $data = $this->db->query($query)->fetch()[0];
            return($data);
        }
        public function db_read_multy($query)
        {
            $data = $this->db->query($query)->fetchAll();
            return($data);
        }
        public function db_change($query)
        {
            $this->db->exec($query);
        }
        public function db_check($query)
        {
            $data = $this->db->query($query)->fetch();
            if(is_array($data))
                return (1);
            return (0);
        }
    }
?>