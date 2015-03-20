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
        $sql = <<<SQL
            SELECT poi.*, COUNT(poi_has_coupon.coupon_id) nb_coupons
            FROM poi
            LEFT JOIN poi_has_coupon ON (poi_has_coupon.poi_id = poi.id)
            GROUP BY poi.id
            ORDER BY name
SQL;

        return $this->db->fetchAll($sql);
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
