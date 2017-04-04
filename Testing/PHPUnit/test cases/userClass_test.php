<?php 
use PHPUnit\Framework\TestCase;

require_once '../classes_and_functions.php';
require_once '../database_connection.php'; //Contains the database connection variable $db

class userClassTest extends TestCase {

    public $ob;
    public $db;

    public function setUp() {
        $this->ob = new userClass();
        $this->db = getConnection();
    }

    //checking checkUser function
    public function test_checkUser() {

        //checking for student
        $result = $this->ob->checkUser("U101114FCS222", $this->db);
        $this->assertEquals("student", $result);

        //checking for faculty
        $result = $this->ob->checkUser("E101114FCS236", $this->db);
        $this->assertEquals("faculty", $result);

        //checking for hod
        $result = $this->ob->checkUser("E101114FCS208", $this->db);
        $this->assertEquals("hod", $result);

        //checking if empty user
        $result = $this->ob->checkUser("", $this->db);
        $this->assertEquals("", $result);

    }


    //checking checkNotifications function
    public function test_checkNotifications() {

        //checking for student
        $result = $this->ob->checkNotifications("U101114FCS222", $this->db);
        foreach($result as $row) {
            $this->assertArrayHasKey("blurb", $row);
            $this->assertArrayHasKey("meetingID", $row);
        }


        //checking for faculty
        $result = $this->ob->checkNotifications("E101114FCS236", $this->db);
        foreach($result as $row) {
            $this->assertArrayHasKey("blurb", $row);
            $this->assertArrayHasKey("meetingID", $row);
        }


        //checking for hod
        $result = $this->ob->checkNotifications("E101114FCS236", $this->db);
        foreach($result as $row) {
            $this->assertArrayHasKey("blurb", $row);
            $this->assertArrayHasKey("meetingID", $row);
        }

    }
}

?>