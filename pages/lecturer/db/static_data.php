<?php declare(strict_types=1);

class StaticData
{
    const roomName = array("LAB123", "MUM321", "SCI123", "COM201", "COS123", "SCI303", "RUS202");
    const roomCapacity = array(20, 25, 25, 30, 30, 15, 20);


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

    const lectureDate = array("2020-02-25","2020-03-03","2020-03-10","2020-03-17","2020-02-24", "2020-03-31", "2020-04-07", "2020-04-14", "2020-04-21", "2020-04-28", "2020-05-05", "2020-05-12");
    const lectureModule = array("DWA", "CSS", "SI", "CS");
    const lectureTime = 1000;
    const lectureStop = 1200;
    const lectureWeek = 1;
    const lecturerId = array(1900020, 1900025, 1900030, 1900035);
    const lectureRoom = array("SCI303", "LAB123", "COM201", "RUS202");
}

?>