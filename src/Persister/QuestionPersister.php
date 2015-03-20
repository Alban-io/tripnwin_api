<?php

namespace TripNWin\Persister;

class QuestionPersister
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        return $this->db->fetchAll('SELECT * FROM poi ORDER BY name');
    }

    public function findOneRandomByPoiId($poiId)
    {
        $sql = <<<SQL
            SELECT *
            FROM question
            WHERE question.poi_id = ?
            ORDER BY RAND()
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $poiId);
        $stmt->execute();

        return $stmt->fetch();
    }
}