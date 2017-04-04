<?php 
use PHPUnit\Framework\TestCase;

require_once '../classes_and_functions.php';
require_once '../database_connection.php'; //Contains the database connection variable $db

class userDetailsTest extends TestCase {

    public $ob;
    public $db;

    public function setUp() {
        $this->ob = new userDetails();
        $this->db = getConnection();
    }

    
    //checking the getUsername function
    public function test_getUsername() {

        //checking for student
        $result = $this->ob->getUsername("U101114FCS222", $this->db);
        $this->assertRegExp("/^[a-z ,.'-]+$/i", $result);

        //checking for faculty
        $result = $this->ob->getUsername("E101114FCS236", $this->db);
        $this->assertRegExp("/^[a-z ,.'-]+$/i", $result);

        //checking for hod
        $result = $this->ob->getUsername("E101114FCS208", $this->db);
        $this->assertRegExp("/^[a-z ,.'-]+$/i", $result);

        //checking if the user is none of them
        $result = $this->ob->getUsername("U101114FCS232", $this->db);
        $this->assertEquals("", $result);
    }


    //checking for getEmail function
    public function test_getEmail() {

        //checking for student
        $result = $this->ob->getEmail("U101114FCS222", $this->db);
        $this->assertRegExp("/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/", $result);

        //checking for faculty
        $result = $this->ob->getEmail("E101114FCS236", $this->db);
        $this->assertRegExp("/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/", $result);

        //checking for hod
        $result = $this->ob->getEmail("E101114FCS208", $this->db);
        $this->assertRegExp("/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/", $result);

        //checking if the user is none of them
        $result = $this->ob->getEmail("U101114FCS232", $this->db);
        $this->assertEquals("", $result);
    }


    //checking for getUserID function
    public function test_getUserID() {

        //checking for student
        $result = $this->ob->getUsername("Rajjat Chhajer", $this->db);
        $valid = is_string($result);
        $this->assertEquals(true, $valid);

        //checking for faculty
        $result = $this->ob->getUsername("Vikas Upadhyaya", $this->db);
        $valid = is_string($result);
        $this->assertEquals(true, $valid);

        //checking for hod
        $result = $this->ob->getUsername("Prosenjit Gupta", $this->db);
        $valid = is_string($result);
        $this->assertEquals(true, $valid);

        //checking if the user is none of them
        $result = $this->ob->getUsername("asdasdsaad", $this->db);
        $this->assertEquals("", $result);
    }
}

?>
