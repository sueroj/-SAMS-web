<?php declare(strict_types=1);

class StaticData
{
    const roomName = array("LAB123", "MUM321", "SCI123", "COM201", "COS123", "SCI303", "RUS202");
    const roomCapacity = array(30, 40, 25, 30, 60, 35, 40);


    const moduleCode = array("CSS", "DWA", "SI", "RM", "CS", "ZE", "ML", "S101");

    const moduleName = array("Computer Systems and Servers",
                            "Developing Web Applications",
                            "Software Implementation",
                            "Research Methods",
                            "CyberSecurity",
                            "Zoo Ethics",
                            "Machine Learning",
                            "Stretching 101"
                            );
    
    const moduleCourseCode = array("ICT", "CS", "AI", "ICT", "CS", "AH", "AI", "SS");

    const courseName = array("Information and Computing Technology",
                            "Computer Science",
                            "Artificial Intelligence",
                            "Sports Science",
                            "Animal Husbandry"
                            );
    
    const courseCode = array("ICT", "CS", "AI", "SS", "AH");

    //function newLecture(){
    //random = new Random();
    //
    // Function for random new lectures here for generating Attendance charts. //
    //
    //
    //}

    const lectureDate = array("2020-02-25","2020-02-26","2020-02-25","2020-03-11","2020-03-03", "2020-04-01", "2020-03-04");
    const lectureModule = array("CSS", "DWA", "SI", "RM", "CS","SS","RM");
    const lectureTime = array(1000, 1100, 900, 1300, 1200, 1300, 900);
    const lectureStop = array(1200, 1300, 1000, 1400, 1500, 1400, 1000);
    const lectureWeek = array(1, 1, 1, 2, 3, 1, 2);
    const lecturerId = array(1862002, 1662342, 1867542, 1991111, 1566302, 1934333, 1722233);
    const lectureRoom = array("LAB123", "MUM321", "COM201", "COM201", "COS123", "RUS202", "SCI303");



}

?>