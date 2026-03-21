<?php 

namespace App\Controllers;

class TestController {
    public function hello($name = "inconnu") {
        echo "Bonjour $name";
    }
}
