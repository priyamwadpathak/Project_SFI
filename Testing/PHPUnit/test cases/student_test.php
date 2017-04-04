<?php 
use PHPUnit\Framework\TestCase;

require_once '../classes_and_functions.php';
require_once '../database_connection.php'; //Contains the database connection variable $db

class studentTest extends TestCase {

    public $ob;
    public $db;

    public function setUp() {
        $this->ob = new student();
        $this->db = getConnection();
    }


    //testing coursesEnrolledIn
    public function test_coursesEnrolledIn() {

        $f = false;
        //checking for student that is enrolled in courses
        $result = $this->ob->coursesEnrolledIn("U101114FCS222", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0) {
            $f = true;
        }
        $this->assertEquals(true, $f);


        $f = false;
        //checking for student that is not enrolled in courses
        $result = $this->ob->coursesEnrolledIn("U101114FCS256", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0) {
            $f = true;
        }
        $this->assertEquals(false, $f);
    }


    //testing avgTopicRating
    public function test_avgTopicRating() {

        //checking for topic that has subtopics
        $result = $this->ob->avgTopicRating("EL101", "2015-2016", "Complement notations and Binary codes", "U101114FCS222", $this->db);
        $valid = is_float($result);
        $this->assertEquals(true, $valid);


        //checking for topic that does not have subtopics
        $result = $this->ob->avgTopicRating("EL101", "2015-2016", "Designing of Arithmetic Combinational circuits", "U101114FCS222", $this->db);
        $this->assertEquals(0, $result);
    }


    
    //testing getFacultyName
    public function test_getFacultyName() {

        //checking for valid courses
        $result = $this->ob->getFacultyName("EL101", $this->db);
        $valid = is_string($result);
        $this->assertEquals(true, $valid);


        //checking for invalid courses
        $result = $this->ob->getFacultyName("EL999", $this->db);
        $this->assertEquals("", $result);
    }
}

?>
