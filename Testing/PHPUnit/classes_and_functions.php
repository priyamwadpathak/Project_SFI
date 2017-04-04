<?php

class userClass {


    //checks the user class of the userID and returns it.
    function checkUser($userID, $db) {

        //Checking for student
        $query = "SELECT name FROM student WHERE enrollmentID='$userID'";
        $result = mysqli_query($db, $query);
        if(mysqli_num_rows($result) > 0)
            return "student";

        //Checking for HOD
        $query = "SELECT DISTINCT faculty.name FROM faculty,hodCoursesIncharge
                    WHERE hodCoursesIncharge.employeeID = '$userID' AND hodCoursesIncharge.employeeID=faculty.employeeID;";
        $result = mysqli_query($db, $query);
        if(mysqli_num_rows($result) > 0)
            return "hod";

        //Checking for faculty
        $query = "SELECT name FROM faculty WHERE employeeID='$userID'";
        $result = mysqli_query($db, $query);
        if(mysqli_num_rows($result) > 0)
            return "faculty";

        //If the user is none of them
        return "";
    }

    //Schedules the meeting and sends an email to the recipient
    function scheduleMeeting($sender, $recipient, $subject, $reason, $db) {

        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO meetings (sender, recipient, subject, reason, status, dateRequested) VALUES ('$sender', '$recipient', '$subject', '$reason',
                    'pending', '$date')";
        mysqli_query($db, $query);

        /*
        //Sending email to the recipient
        $userDetails_ob = new userDetails();
        $recipientMail = $userDetails_ob->getEmail($recipient, $db);
        $senderName = $userDetails_ob->getUsername($sender, $db);

        $subject = "New meeting request";

        $message = "$senderName sent you a meeting request via The Student Faculty Interaction platform";

        //Set the mail headers into a variable
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
        $headers .= "From: $name <$email> \r\n";
        $headers .= "X-Priority: 1\r\n";
        $headers .= "X-MSMail-Priority: High\r\n\r\n";

        //Send the email!
        mail($recipientMail, $subject, $message, $headers);
        */


        //Updating the notification table
        $meetingID = mysqli_insert_id($db);
        //echo $meetingID;
        $query = "INSERT INTO notifications (meetingID, recipient, status) VALUES ('$meetingID', '$recipient', 'unseen')";
        mysqli_query($db, $query);

    }


    //Returns the unseen notifications
    function checkNotifications($userID, $db) {

        $query = "SELECT meetingID FROM notifications WHERE recipient='$userID' AND status='unseen'";
        $result = mysqli_query($db, $query);

        $notifications = array();

        if(mysqli_num_rows($result) > 0) {

            foreach($result as $row) {

                $meetingID = $row['meetingID'];
                $query = "SELECT meetingID,sender,date_format(dateRequested, '%Y-%m-%d') AS dateFormat FROM meetings WHERE meetingID='$meetingID'";
                $result2 = mysqli_query($db, $query);

                if(mysqli_num_rows($result2) > 0) {

                    foreach($result2 as $row2) {
                        $sender = $row2['sender'];
                        $userDetails_ob = new userDetails();
                        $sender = $userDetails_ob->getUsername($sender, $db);

                        $date = strtotime($row2['dateFormat']);
                        $date = date('d F, Y', $date);
                        $str = "You have a new meeting request from $sender, sent on $date.";
                        $notifications[] = array('blurb' => $str, 'meetingID' => $row2['meetingID'] );
                    }
                }
            }
        }

        else {
            $notifications[] = array('blurb' => "You have no new notifications.", 'meetingID' => -'1' );
        }

        return $notifications;

    }

}







class userDetails {

    //Returns the Username for every user class
    function getUsername($userID, $db) {

        //Checking for student
        $query = "SELECT name FROM student WHERE enrollmentID='$userID'";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            foreach($result as $row)
                return $row['name'];
        }

        //Checking for HOD
        $query = "SELECT DISTINCT faculty.name FROM faculty,hodCoursesIncharge
                    WHERE hodCoursesIncharge.employeeID = '$userID' AND hodCoursesIncharge.employeeID=faculty.employeeID;";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            foreach($result as $row)
                return $row['name'];
        }

        //Checking for faculty
        $query = "SELECT name FROM faculty WHERE employeeID='$userID'";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            foreach($result as $row)
                return $row['name'];
        }

        //If the user is none of them
        return "";

    }


    //Returns the emailID for every user class
    function getEmail($userID, $db) {

        //Checking for student
        $query = "SELECT email FROM student WHERE enrollmentID='$userID'";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            foreach($result as $row)
                return $row['email'];
        }

        //Checking for HOD
        $query = "SELECT DISTINCT faculty.email FROM faculty,hodCoursesIncharge
                    WHERE hodCoursesIncharge.employeeID = '$userID' AND hodCoursesIncharge.employeeID=faculty.employeeID;";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            foreach($result as $row)
                return $row['email'];
        }

        //Checking for faculty
        $query = "SELECT email FROM faculty WHERE employeeID='$userID'";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            foreach($result as $row)
                return $row['email'];
        }

        //If the user is none of them
        return "";

    }



    //Returns the userId of every user class
    function getUserID($userName, $db) {

        //Checking for student
        $query = "SELECT enrollmentID FROM student WHERE name='$userName'";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            foreach($result as $row)
                return $row['enrollmentID'];
        }

        //Checking for faculty
        $query = "SELECT employeeID FROM faculty WHERE name='$userName'";
        $result = mysqli_query($db, $query);

        if(mysqli_num_rows($result) > 0) {
            foreach($result as $row)
                return $row['employeeID'];
        }

        //If the user is none of them
        return "";

    }

}






class courseHandout {

    //for HTTP header injections
    function stripBadChars($input) {
        $output = preg_replace("/[^a-zA-Z0-9_-]/", "", $input);
        return $output;
    }

    //Returns the course name and the sem
    function getCourseTitle($courseCode, $session, $db) {

        $query = "SELECT courseName,sem FROM courses WHERE courseCode='$courseCode' AND session='$session'";
        $result = mysqli_query($db, $query);
        return $result;
    }



    //Returns the topics and topicStatus
    function getTopics($courseCode, $session, $db) {

        $query = "SELECT topic,topicStatus FROM courseTopics WHERE courseCode='$courseCode'AND session='$session'";
        $result = mysqli_query($db, $query);
        return $result;
    }



    //Returns the subtopic and subtopicStatus of the topic given
    function getSubtopics($courseCode, $session, $topic, $db) {

        $query = "SELECT subtopic,subtopicStatus FROM courseSubtopics WHERE courseCode='$courseCode'AND session='$session' AND topic='$topic'";
        $result = mysqli_query($db, $query);
        return $result;
    }



    //Returns the topicLinks of the given topic
    function getTopicLink($courseCode, $session, $topic, $db) {

        $query = "SELECT topicLink FROM courseTopicLinks WHERE courseCode='$courseCode' AND session='$session' AND topic='$topic'";
        $result = mysqli_query($db, $query);
        return $result;
    }



    //Returns the subtopicLinks of the subtopic
    function getSubtopicLink($courseCode, $session, $topic, $subtopic, $db) {

        $query = "SELECT subtopicLink FROM courseSubtopicLinks WHERE courseCode='$courseCode' AND session='$session' AND topic='$topic'
                    AND subtopic='$subtopic'";
        $result = mysqli_query($db, $query);
        return $result;
    }


    //Returns the topic rating for the individual student of the topic
    function student_getTopicRating($courseCode, $session, $topic, $enrollmentID, $db) {

        $query = "SELECT topicRating FROM courseTopicRating
                    WHERE courseCode='$courseCode' AND session='$session' AND topic='$topic' AND enrollmentID='$enrollmentID'";
        $result = mysqli_query($db, $query);
        return $result;
    }


    //Returns the subtopic rating for the individual student of the subtopic
    function student_getSubtopicRating($courseCode, $session, $topic, $subtopic, $enrollmentID, $db) {

        $query = "SELECT subtopicRating FROM courseSubtopicRating
                    WHERE courseCode='$courseCode' AND session='$session' AND topic='$topic' AND subtopic='$subtopic' AND enrollmentID='$enrollmentID'";
        $result = mysqli_query($db, $query);
        return $result;
    }

}





class faculty extends userDetails {

    //Returns the research area of faculty
    function getResearch($userID, $db) {

        $query = "SELECT researchArea FROM faculty WHERE employeeID='$userID'";
        $result = mysqli_query($db, $query);

        foreach($result as $row) {
            return $row['researchArea'];
        }
    }


    //Returns the courses the faculty is currently teaching
    function courseConducting($userID, $session, $db) {

        $query = "SELECT courses.courseName,courses.courseCode FROM courses,facultyCoursesConducting
                    WHERE facultyCoursesConducting.employeeID='$userID' AND facultyCoursesConducting.session='$session'
                    AND facultyCoursesConducting.courseCode = courses.courseCode";
        $result = mysqli_query($db, $query);
        return $result;
    }


    //Toggles the checked column of the corresponding topic in database
    function topicStatus_Update($courseCode, $session, $topic, $topicStatus, $db) {

        $topic = urldecode($topic); //decoding the string sent in encoded form through ajax
        $date = date("Y-m-d H:i:s");
        $query = "UPDATE courseTopics SET topicStatus='$topicStatus', topicStatusTimestamp='$date' WHERE courseCode='$courseCode'
                    AND session='$session' AND topic='$topic'";
        mysqli_query($db, $query);
        echo $topic;
    }


    //Toggles the checked column of the corresponding subtopic in database
    function subtopicStatus_Update($courseCode, $session, $topic, $subtopic, $subtopicStatus, $db) {

        $topic = urldecode($topic); //decoding the string sent in encoded form through ajax
        $subtopic = urldecode($subtopic); //decoding the string sent in encoded form through ajax
        $date = date("Y-m-d H:i:s");
        $query = "UPDATE courseSubtopics SET subtopicStatus='$subtopicStatus', subtopicStatusTimestamp='$date' WHERE courseCode='$courseCode'
                    AND session='$session' AND topic='$topic' AND subtopic='$subtopic'";
        mysqli_query($db, $query);
        //echo $session;

        $query2 = "SELECT subtopicStatus FROM courseSubtopics WHERE courseCode='$courseCode' AND session='$session' AND topic='$topic'";
        $result = mysqli_query($db, $query2);

        $f = true; //flag

        foreach($result as $row) {
            if($row['subtopicStatus'] != 'checked') {
                $f = false;
                break;
            }
        }

        echo $f;
    }



    //Returns the average rating of topic by all the students. Returns the answer after processing and not in query result form
    function faculty_getTopicRating($courseCode, $session, $topic, $db) {

        $query = "SELECT AVG(topicRating) AS AVG FROM courseTopicRating WHERE courseCode='$courseCode' AND session='$session' AND topic='$topic'";
        $result = mysqli_query($db, $query);

        foreach($result as $row)
            return round($row['AVG']);
    }


    //Returns the average rating of subtopic by all the students. Returns the answer after processing and not in query result form
    function faculty_getSubtopicRating($courseCode, $session, $topic, $subtopic, $db) {

        $query = "SELECT AVG(subtopicRating) AS AVG FROM courseSubtopicRating WHERE courseCode='$courseCode' AND session='$session' AND topic='$topic' AND subtopic='$subtopic'";
        $result = mysqli_query($db, $query);

        foreach($result as $row)
            return round($row['AVG']);
    }



    //Returns the frequency of the topic in an associative array
    function topic_getFrequency($courseCode, $session, $topic, $db) {

        $frequency = array();

        //calculating the no. of 5 raters
        $query = "SELECT COUNT(topicRating) AS FREQ FROM courseTopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND topicRating='5'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['five'] = $row['FREQ'];
        }


        //calculating the no. of 4 raters
        $query = "SELECT COUNT(topicRating) AS FREQ FROM courseTopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND topicRating='4'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['four'] = $row['FREQ'];
        }


        //calculating the no. of 3 raters
        $query = "SELECT COUNT(topicRating) AS FREQ FROM courseTopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND topicRating='3'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['three'] = $row['FREQ'];
        }


        //calculating the no. of 2 raters
        $query = "SELECT COUNT(topicRating) AS FREQ FROM courseTopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND topicRating='2'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['two'] = $row['FREQ'];
        }


        //calculating the no. of 1 raters
        $query = "SELECT COUNT(topicRating) AS FREQ FROM courseTopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND topicRating='1'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['one'] = $row['FREQ'];
        }

        return $frequency;

    }



    //Returns the frequency of the subtopic in an associative array
    function subtopic_getFrequency($courseCode, $session, $topic, $subtopic, $db) {

        $frequency = array();

        //calculating the no. of 5 raters
        $query = "SELECT COUNT(subtopicRating) AS FREQ FROM courseSubtopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND subtopic='$subtopic' AND subtopicRating='5'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['five'] = $row['FREQ'];
        }


        //calculating the no. of 4 raters
        $query = "SELECT COUNT(subtopicRating) AS FREQ FROM courseSubtopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND subtopic='$subtopic' AND subtopicRating='4'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['four'] = $row['FREQ'];
        }


        //calculating the no. of 3 raters
        $query = "SELECT COUNT(subtopicRating) AS FREQ FROM courseSubtopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND subtopic='$subtopic' AND subtopicRating='3'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['three'] = $row['FREQ'];
        }


        //calculating the no. of 2 raters
        $query = "SELECT COUNT(subtopicRating) AS FREQ FROM courseSubtopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND subtopic='$subtopic' AND subtopicRating='2'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['two'] = $row['FREQ'];
        }


        //calculating the no. of 1 raters
        $query = "SELECT COUNT(subtopicRating) AS FREQ FROM courseSubtopicRating WHERE courseCode='$courseCode' AND session='$session'
                    AND topic='$topic' AND subtopic='$subtopic' AND subtopicRating='1'";
        $result = mysqli_query($db, $query);
        foreach($result as $row) {
            $frequency['one'] = $row['FREQ'];
        }

        return $frequency;

    }



}






class hod extends faculty {

    //Returns the courseName and courseCode HOD is incharge of
    function courseIncharge($userID, $session, $db) {

        $query = "SELECT courses.courseName, courses.courseCode FROM courses,hodCoursesIncharge
                    WHERE hodCoursesIncharge.employeeID='$userID' AND hodCoursesIncharge.session='$session'
                    AND hodCoursesIncharge.courseCode = courses.courseCode";

        $result = mysqli_query($db, $query);
        return $result;
    }
}







class student extends userDetails {

    //Returns the courseName and courseCode student is enrolled in
    function coursesEnrolledIn($userID, $session, $db) {
        $query = "SELECT courses.courseName, courses.courseCode FROM courses,studentCoursesEnrolled
                    WHERE studentCoursesEnrolled.enrollmentID='$userID' AND studentCoursesEnrolled.session='$session'
                    AND studentCoursesEnrolled.courseCode = courses.courseCode";

        $result = mysqli_query($db, $query);
        return $result;
    }



    //Calculates the average topic rating based on subtopics
    function avgTopicRating($courseCode, $session, $topic, $enrollmentID, $db) {

        $query = "SELECT AVG(subtopicRating) AS AVG FROM courseSubtopicRating WHERE courseCode='$courseCode' AND session='$session' AND topic='$topic' and enrollmentID='$enrollmentID'";
        $result = mysqli_query($db, $query);

        foreach($result as $row) {
            return round($row['AVG']);
        }
    }



    //Enters the topicRating of the topic rated by the student
    function rateTopics($courseCode, $session, $topic, $enrollmentID, $topicRating, $previouslyRated, $db) {

        $topic = urldecode($topic); //decoding the string sent in encoded form through ajax
        $date = date("Y-m-d H:i:s");

        if($previouslyRated == 'false')
            $query = "INSERT INTO courseTopicRating (courseCode, session, topic, enrollmentID, topicRating, topicRatingTimestamp) VALUES
                        ('$courseCode', '$session', '$topic', '$enrollmentID', '$topicRating', '$date')";

        else
            $query = "UPDATE courseTopicRating SET topicRating='$topicRating', topicRatingTimestamp='$date' WHERE courseCode='$courseCode' AND
                        session='$session' AND topic='$topic' AND enrollmentID='$enrollmentID'";

        mysqli_query($db, $query);
    }



    //Enters the subtopicRating of the subtopics rated by the student
    function rateSubtopics($courseCode, $session, $topic, $subtopic, $enrollmentID, $subtopicRating, $previouslyRated, $db) {

        $topic = urldecode($topic); //decoding the string sent in encoded form through ajax
        $subtopic = urldecode($subtopic); //decoding the string sent in encoded form through ajax
        $date = date("Y-m-d H:i:s");

        if($previouslyRated == 'false')
            $query = "INSERT INTO courseSubtopicRating (courseCode, session, topic, subtopic, enrollmentID, subtopicRating, subtopicRatingTimestamp) VALUES
                        ('$courseCode', '$session', '$topic', '$subtopic', '$enrollmentID', '$subtopicRating', '$date')";

        else
            $query = "UPDATE courseSubtopicRating SET subtopicRating='$subtopicRating', subtopicRatingTimestamp='$date' WHERE courseCode='$courseCode' AND
                session='$session' AND topic='$topic' AND subtopic='$subtopic' AND enrollmentID='$enrollmentID'";

        mysqli_query($db, $query);

        $result = self::avgTopicRating($courseCode, $session, $topic, $enrollmentID, $db);

        //Calling the getTopicRating function to get whether the topic was previously rated or not
        $courseHandout_ob = new courseHandout();
        $previouslyRated = false;
        $result2 = $courseHandout_ob->student_getTopicRating($courseCode, $session, $topic, $enrollmentID, $db);
        if(mysqli_num_rows($result2) > 0)
            $previouslyRated = true;

        echo round($result);
        echo $previouslyRated; //This will append to the previous echo, 0 for false, 1 for true
    }



    //Returns the name of the faculty who is conducting the courseConducting
    function getfacultyName($courseCode, $db) {

        $query = "SELECT faculty.name FROM faculty,facultyCoursesConducting
                    WHERE facultyCoursesConducting.courseCode='$courseCode' AND facultyCoursesConducting.employeeID = faculty.employeeID";
        $result = mysqli_query($db, $query);

        foreach($result as $row) {
            return $row['name'];
        }
    }
}

?>
