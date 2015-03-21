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

    public function findAllByPoiIdWithUserStatus($poiId, $userId)
    {
        $sql = <<<SQL
            SELECT coupon.*, COUNT(user_won_coupon.user_id) already_won
            FROM coupon
            JOIN poi_has_coupon ON (poi_has_coupon.coupon_id = coupon.id)
            LEFT JOIN user_won_coupon ON (user_won_coupon.coupon_id = coupon.id)
            WHERE poi_has_coupon.poi_id = ?
            AND (user_won_coupon.user_id IS NULL OR user_won_coupon.user_id = ?)
            GROUP BY coupon.id
            ORDER BY RAND()
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $poiId);
        $stmt->bindValue(2, $userId);
        $stmt->execute();

        $coupons = $stmt->fetchAll();

        foreach ($coupons as &$coupon) {
            $coupon['already_won'] = $coupon['already_won'] == 1;
        }

        return $coupons;
    }

    public function findOneByIdAndPoiIdWithUserStatus($couponId, $poiId, $userId)
    {
        $sql = <<<SQL
            SELECT coupon.*, COUNT(user_won_coupon.user_id) already_won
            FROM coupon
            JOIN poi_has_coupon ON (poi_has_coupon.coupon_id = coupon.id)
            LEFT JOIN user_won_coupon ON (user_won_coupon.coupon_id = coupon.id)
            WHERE poi_has_coupon.poi_id = ?
            AND coupon.id = ?
            AND (user_won_coupon.user_id IS NULL OR user_won_coupon.user_id = ?)
SQL;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $poiId);
        $stmt->bindValue(2, $couponId);
        $stmt->bindValue(3, $userId);
        $stmt->execute();

        $coupon = $stmt->fetch();

        if (false !== $coupon) {
            $coupon['already_won'] = $coupon['already_won'] == 1;
        }

        return $coupon;
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