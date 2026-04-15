<?php 

class Project {
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }
    
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM projects");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO projects (title, status, description, progress)
                VALUES (:title, :status, :description, :progress)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'title' => $data['title'],
            'status' => $data['status'],
            'description' => $data['description'],
            'progress' => $data['progress']
        ]);
    }
}