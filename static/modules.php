<?php declare(strict_types=1);
include "rooms.php";

class Module
{
    private $name;

    private $room;

    private $start_date;

    private $end_date;

    private $attendance;

    private $enrolled;

    private $capacity;

    function __construct(string $_name, string $_room, string $_start_date, string $_end_date, int $_attendance, int $_enrolled, int $capacity)
    {
        $this->name = $name;
        $this->room = $room;
        $this->start_date = $_start_date;
        $this->end_date = $_end_date;
        $this->attendance = $_attendance;
        $this->enrolled = $_enrolled;
        $this->capacity = $_capacity;
    }



//     const module = "Computer Systems and Servers";
//     const room = Rooms::roomName[1];
//     const start_date = "2020-02-01 13:00:00"; //This range includes range for module start/end date AND the start/end time for each lecture.
//     const end_date = "2020-05-01 15:00:00";  //Format YYYY-MM-DD(Start/Stop Module) HH:MM:SS (Start/Stop lecture)
//     const attendance = 0;     //0 until algorithm is implemented.
//     const enrolled = 0;       //0 until algorithm is implemented.
//     const capacity = Rooms::roomCapacity[1];
}
?>