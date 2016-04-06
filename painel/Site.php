<?php

class Site extends SiteModel {

    public $db;
    public $registry;

    public function __construct($registry = null) {
        parent::__construct($registry);
    }

}
