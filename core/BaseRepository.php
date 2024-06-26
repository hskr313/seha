<?php
class BaseRepository {
    protected $db;
    protected $table;
    protected $entityClass;

    public function __construct(string $table, string $entityClass) {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
        $this->table = $table;
        $this->entityClass = $entityClass;
    }

    public function findAll(): array {
        $result = $this->db->query("SELECT * FROM {$this->table}");
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'mapToEntity'], $rows);
    }

    public function findById(int $id): ?object {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die ("Database query failed: " . $this->db->error);
        }

        $data = $result->fetch_assoc();

        return $data ? $this->mapToEntity($data) : null;
    }

    public function create(object $entity): bool {
        $data = $this->extract($entity);
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $values = array_values($data);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param(str_repeat('s', count($values)), ...$values);
        return $stmt->execute();
    }

    public function update(int $id, object $entity): bool {
        $data = $this->extract($entity);
        $sets = [];
        $values = [];
        foreach ($data as $column => $value) {
            $sets[] = "$column = ?";
            // Convert the boolean value to an integer (0 or 1)
            if (gettype($value) == 'boolean') {
                $value = (int)$value;
            }
            $values[] = $value;
        }
        $values[] = $id;
        $setString = implode(", ", $sets);

        $stmt = $this->db->prepare("UPDATE {$this->table} SET $setString WHERE id = ?");

        if (!$stmt) {
            error_log('SQL prepare failed: ' . $this->db->error);
            return false;
        }

        $bindTypes = '';

        foreach ($data as $_ => $value) {
            if (is_int($value)) {
                $bindTypes .= 'i';
            } elseif (is_float($value)) {
                $bindTypes .= 'd';
            } else {
                $bindTypes .= 's';
            }
        }

        $bindTypes .= 'i'; // for the id

        $stmt->bind_param($bindTypes, ...$values);
        $executeResult = $stmt->execute();

        if (!$executeResult) {
            error_log('Execute failed: ' . $stmt->error);
        }

        return $executeResult;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function findByCriteria(array $criteria): array {
        $query = "SELECT * FROM {$this->table} WHERE ";
        $conditions = [];
        $values = [];
        foreach ($criteria as $column => $value) {
            $conditions[] = "$column = ?";
            $values[] = $value;
        }
        $query .= implode(" AND ", $conditions);

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param(str_repeat('s', count($values)), ...$values);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'mapToEntity'], $rows);
    }

    /**
     * @throws ReflectionException
     */
    protected function mapToEntity(array $data): object {
        $reflect = new ReflectionClass($this->entityClass);
        $entity = $reflect->newInstanceWithoutConstructor();
        foreach ($data as $key => $value) {
            if ($reflect->hasProperty($key)) {
                $property = $reflect->getProperty($key);
                $property->setValue($entity, $value);
            }
        }
        return $entity;
    }

    protected function extract(object $entity): array {
        $reflect = new ReflectionClass($entity);
        $properties = $reflect->getProperties();
        $data = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $data[$property->getName()] = $property->getValue($entity);
        }
        return $data;
    }

    /**
     * @throws ReflectionException
     */
    public function getEntityProperties(): array {
        $reflect = new ReflectionClass($this->entityClass);
        $properties = $reflect->getProperties();
        return array_map(function($property) {
            return $property->getName();
        }, $properties);
    }
}
