<?php

namespace TripNWin\Persister;

use Doctrine\DBAL\Connection;

class PoiPersister
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        return $this->db->fetchAll('SELECT * FROM poi ORDER BY name');
    }

    public function findOneById($id)
    {
        $sql = 'SELECT * FROM poi WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function create($poi) {
        $this->db->insert('poi', $poi);
    }


}
