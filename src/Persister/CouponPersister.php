<?php

namespace TripNWin\Persister;

use Doctrine\DBAL\Connection;

class CouponPersister
{
    private $db;

    public function __construct(Connection $db)
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

    public function findOneByIdAndPoiId($couponId, $poiId)
    {
        $sql = <<<SQL
            SELECT coupon.*
            FROM coupon
            JOIN poi_has_coupon ON (poi_has_coupon.coupon_id = coupon.id)
            WHERE poi_has_coupon.poi_id = ?
            AND coupon.id = ?
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $poiId);
        $stmt->bindValue(2, $couponId);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function addWinner($couponId, $userId)
    {
        $sql = 'INSERT IGNORE INTO user_won_coupon VALUES (?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId);
        $stmt->bindValue(2, $couponId);
        $stmt->execute();
    }
}