<?php

namespace TripNWin\Persister;

class CouponPersister
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAllByPoiId($poiId)
    {
        $sql = <<<SQL
            SELECT coupon.*
            FROM coupon
            JOIN poi_has_coupon ON (poi_has_coupon.coupon_id = coupon.id)
            WHERE poi_has_coupon.poi_id = ?
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $poiId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}