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

    public function findNearBy($latitude, $longitude, $radius)
    {
        $latitudeRad  = deg2rad($latitude);
        $longitudeRad = deg2rad($longitude);
        $latitudeCos  = cos($latitudeRad);
        $latitudeSin  = sin($latitudeRad);

        $sql = <<<SQL
            SELECT poi.*, COUNT(poi_has_coupon.coupon_id) nb_coupons, ROUND(DISTANCE(?, ?, ?, poi.latitude_cos, poi.latitude_sin, poi.longitude_rad)) distance
            FROM poi
            LEFT JOIN poi_has_coupon ON (poi_has_coupon.poi_id = poi.id)
            WHERE DISTANCE(?, ?, ?, poi.latitude_cos, poi.latitude_sin, poi.longitude_rad) <= ?
            GROUP BY poi.id
            ORDER BY name
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $latitudeCos);
        $stmt->bindValue(2, $latitudeSin);
        $stmt->bindValue(3, $longitudeRad);
        $stmt->bindValue(4, $latitudeCos);
        $stmt->bindValue(5, $latitudeSin);
        $stmt->bindValue(6, $longitudeRad);
        $stmt->bindValue(7, $radius);
        $stmt->execute();

        return $stmt->fetchAll();
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
