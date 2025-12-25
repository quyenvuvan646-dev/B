<?php
require_once 'BaseModel.php';

class NewsModel extends BaseModel
{
    protected $table = 'news';

    public function create($title, $content, $image, $authorEmail, $isPublished = 1)
    {
        $sql = "INSERT INTO {$this->table} (title, content, image, author_email, is_published, created_at) VALUES (:title, :content, :image, :author_email, :is_published, NOW())";
        return $this->query($sql, [
            ':title' => $title,
            ':content' => $content,
            ':image' => $image,
            ':author_email' => $authorEmail,
            ':is_published' => (int)$isPublished
        ]);
    }

    public function updateNews($id, $title, $content, $image, $isPublished)
    {
        $sets = [ 'title = :title', 'content = :content', 'is_published = :is_published' ];
        $params = [ ':id' => $id, ':title' => $title, ':content' => $content, ':is_published' => (int)$isPublished ];
        if ($image !== null) { $sets[] = 'image = :image'; $params[':image'] = $image; }
        $sql = "UPDATE {$this->table} SET ".implode(', ', $sets).", updated_at = NOW() WHERE id = :id";
        return $this->query($sql, $params);
    }

    public function setPublished($id, $isPublished)
    {
        $sql = "UPDATE {$this->table} SET is_published = :p, updated_at = NOW() WHERE id = :id";
        return $this->query($sql, [ ':p' => (int)$isPublished, ':id' => $id ]);
    }

    public function deleteNews($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [ $id ]);
    }

    public function getNewsById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $rows = $this->select($sql, [ $id ]);
        return $rows ? $rows[0] : null;
    }

    public function getAllNews()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->select($sql, []);
    }

    public function getPublishedNews($limit = 20, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_published = 1 ORDER BY created_at DESC LIMIT :offset, :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
