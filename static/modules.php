<?php
include "rooms.php";

class Modules
{
    const module = "Computer Systems and Servers";
    const room = Rooms::roomName[1];
    const start_date = "2020-02-01 13:00:00"; //This range includes range for module start/end date AND the start/end time for each lecture.
    const end_date = "2020-05-01 15:00:00";  //Format YYYY-MM-DD(Start/Stop Module) HH:MM:SS (Start/Stop lecture)
    const attendance = 0;     //0 until algorithm is implemented.
    const enrolled = 0;       //0 until algorithm is implemented.
    const capacity = Rooms::roomCapacity[1];
}
?>