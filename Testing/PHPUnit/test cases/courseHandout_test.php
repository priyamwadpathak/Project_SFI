<?php 
use PHPUnit\Framework\TestCase;

require_once '../classes_and_functions.php';
require_once '../database_connection.php'; //Contains the database connection variable $db

class courseHandoutTest extends TestCase {

    public $ob;
    public $db;

    public function setUp() {
        $this->ob = new courseHandout();
        $this->db = getConnection();
    }

    //testing stripBadChars function
    public function test_stripBadChars() {

        //checking for normal inpput
        $result = $this->ob->stripBadChars("asdasdasd45465465");
        $this->assertEquals("asdasdasd45465465", $result);

        //checking for abormal input
        $result = $this->ob->stripBadChars("$.!@");
        $this->assertEquals("", $result);
    }


    //testing getCourseTitle function
    public function test_getCourseTitle() {

        $f = false;
        //checking for courseCode present in database
        $result = $this->ob->getCourseTitle("EL101", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f);


        $f = false;
        //checking for courseCode not present in database
         $result = $this->ob->getCourseTitle("EL99", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);

    }


    //testing getTopics function
    public function test_getTopics() {

        $f = false;
        //checking for courseCode present in database
        $result = $this->ob->getTopics("EL101", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f);


        $f = false;
        //checking for courseCode not present in database
        $result = $this->ob->getTopics("EL96", "2015-2016", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);
    }


    //testing getSubtopics function
    public function test_getSubtopics() {

        $f = false;
        //checking for topics which have subtopics present in database
        $result = $this->ob->getSubtopics("EL101", "2015-2016", "Complement notations and Binary codes", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f);


         $f = false;
        //checking for topics which do not have subtopics present in database
        $result = $this->ob->getSubtopics("EL101", "2015-2016", "Designing of Arithmetic Combinational circuits", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);
    }


    //testing getTopicLink
    public function test_getTopicLink() {

        $f = false;
        //checking for topics which have links present in database
        $result = $this->ob->getTopicLink("EL101", "2015-2016", "Analog & Digital signals and Number system", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f);  

        
        $f = false;
        //checking for topics which do not have links in database
        $result = $this->ob->getSubtopics("EL101", "2015-2016", "Designing of Arithmetic Combinational circuits", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);
    }


    //testing getSubtopicLink
    public function test_getSubtopicLink() {

        $f = false;
        //checking for subtopics which have links present in database
        $result = $this->ob->getSubtopicLink("EL101", "2015-2016", "Complement notations and Binary codes", "10s complement", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f);  

        
        $f = false;
        //checking for subtopics which do not have links in database
        $result = $this->ob->getSubtopicLink("EL101", "2015-2016", "Analog & Digital signals and Number system", "Binary number system", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);
    }


    //testing student_getTopicRating
    public function test_student_getTopicRating() {

        $f = false;
        //checking for topics which have been rated by a student
        $result = $this->ob->student_getTopicRating("EL101", "2015-2016", "Complement notations and Binary codes", "U101114FCS222", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f); 


        $f = false;
        //checking for topics which have not been rated by a student
        $result = $this->ob->student_getTopicRating("EL101", "2015-2016", "Logic gates and Logic & Arithmetic operations", "U101114FCS222", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);
    }


    //testing student_getSubtopicRating
    public function test_student_getSubtopicRating() {

        $f = false;
        //checking for subtopics which have been rated by a student
        $result = $this->ob->student_getSubtopicRating("EL101", "2015-2016", "Complement notations and Binary codes", "1s complement", "U101114FCS222", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(true, $f); 


        $f = false;
        //checking for subtopics which have not been rated by a student
        $result = $this->ob->student_getSubtopicRating("EL101", "2015-2016", "Logic gates and Logic & Arithmetic operations", "1s complement", "U101114FCS222", $this->db);
        if(mysqli_num_rows($result) > 0)
            $f = true;
        $this->assertEquals(false, $f);
    }

}

?>
