<?php 
use PHPUnit\Framework\TestCase;

require_once '../classes_and_functions.php';
require_once '../database_connection.php'; //Contains the database connection variable $db

class facultyTest extends TestCase {

    public $ob;
    public $db;

    public function setUp() {
        $this->ob = new faculty();
        $this->db = getConnection();
    }


    
    //testing getResearch
    public function test_getResearch() {

        //for faculty that is in the database
        $result = $this->ob->getResearch("E101114FCS208", $this->db);
        $valid = is_string($result);
        $this->assertEquals(true, $valid);

        //for faculty that is not in the database
        $result = $this->ob->getResearch("E101114FCS258", $this->db);
        $this->assertEquals("", $result);
    }



    //testing courseConducting
    public function test_courseConducting() {

        $f = false;
        //for faculty that is in the database
        $result = $this->ob->courseConducting("E101114FCS236", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f);


         $f = false;
        //for faculty that is not in the database
        $result = $this->ob->courseConducting("U101114FCS228", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);
    }



    //testing faculty_getTopicRating
    public function test_faculty_getTopicRating() {

        //for topic that has been rated
        $result = $this->ob->faculty_getTopicRating("EL101", "2015-2016", "Analog & Digital signals and Number system", $this->db);
        $valid = is_float($result);
        $this->assertEquals(true, $valid);


        //for topic that has not been rated
        $result = $this->ob->faculty_getTopicRating("EL101", "2015-2016", "Logic gates and Logic & Arithmetic operations", $this->db);
        $this->assertEquals(0, $result);
    }


    
    //testing faculty_getSubtopicRating
    public function test_faculty_getSubtopicRating() {

        //for subtopic that has been rated
        $result = $this->ob->faculty_getSubtopicRating("EL101", "2015-2016", "Analog & Digital signals and Number system", "1s complement", $this->db);
        $valid = is_float($result);
        $this->assertEquals(true, $valid);


        //for subtopic that has not been rated
        $result = $this->ob->faculty_getSubtopicRating("EL101", "2015-2016", "Logic gates and Logic & Arithmetic operations", "1s complement", $this->db);
        $this->assertEquals(0, $result);
    } 



    //testing topic_getFrequency
    public function test_topic_getFrequency() {

        //for topic that has been rated
        $result = $this->ob->topic_getFrequency("EL101", "2015-2016", "Analog & Digital signals and Number system", $this->db);
        $this->assertArrayHasKey("five", $result);
        $this->assertArrayHasKey("four", $result);
        $this->assertArrayHasKey("three", $result);
        $this->assertArrayHasKey("two", $result);
        $this->assertArrayHasKey("one", $result); 

        
        //for topic that has not been rated
        $result = $this->ob->topic_getFrequency("EL101", "2015-2016", "Logic gates and Logic & Arithmetic operations", $this->db);
        foreach($result as $key=>$value) {
            $this->assertEquals(0, $value);
        }
    }


    //testing subtopic_getFrequency
    public function test_subtopic_getFrequency() {

        //for topic that has been rated
        $result = $this->ob->subtopic_getFrequency("EL101", "2015-2016", "Complement notations and Binary codes", "1s complement", $this->db);
        $this->assertArrayHasKey("five", $result);
        $this->assertArrayHasKey("four", $result);
        $this->assertArrayHasKey("three", $result);
        $this->assertArrayHasKey("two", $result);
        $this->assertArrayHasKey("one", $result); 

        
        //for topic that has not been rated
        $result = $this->ob->subtopic_getFrequency("EL101", "2015-2016", "Logic gates and Logic & Arithmetic operations", "1s complement", $this->db);
        foreach($result as $key=>$value) {
            $this->assertEquals(0, $value);
        }
    }
}

?>
