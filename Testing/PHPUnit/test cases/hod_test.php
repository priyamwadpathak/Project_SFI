<?php 
use PHPUnit\Framework\TestCase;

require_once '../classes_and_functions.php';
require_once '../database_connection.php'; //Contains the database connection variable $db

class hodTest extends TestCase {

    public $ob;
    public $db;

    public function setUp() {
        $this->ob = new hod();
        $this->db = getConnection();
    }


    //testing courseIncharge
    public function test_courseIncharge() {

        $f = false;
        //checking for user that is hod
        $result = $this->ob->courseIncharge("E101114FCS208", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f);


        $f = false;
        //checking for user that is not hod
        $result = $this->ob->courseIncharge("E101114FCS246", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);
    }
}

?>
