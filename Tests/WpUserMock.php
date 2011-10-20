<?php
# Mock WP_User class so we don't need WordPress to run tests
class WP_User
{
    public function __construct() { }
}