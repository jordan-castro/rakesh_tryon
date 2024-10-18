<?php

class DB {
    public ?PDO $pdo;
    private bool $isActive = true;

    public function __construct()
    {
        $this->pdo = new PDO("sqlite:" . DB_NAME_PATH);
    }

    public function __destruct()
    {
        if ($this->isActive) {
            $this->close();
        }
    }

    public function createStmt($sqlString) {
        return $this->pdo->prepare($sqlString);
    }

    public function execute($string) {
        return $this->pdo->exec($string);
    }

    public function close() {
        $this->pdo = null;
        $this->isActive = false;
    }

    public function parseRow($row, $default=null) {
        $res = $row->fetchAll(PDO::FETCH_ASSOC);

        if (!$res) {
            return $default;
        }

        return $res;
    }
}
