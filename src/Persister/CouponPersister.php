<?php

class CouponPersister
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}