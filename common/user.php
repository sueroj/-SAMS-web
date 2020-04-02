<?php
//Abstract class intended to be used by the 3 main classes: Student, Lecturer, Administrator.

abstract class User
{
    const Student = 0;

    const Lecturer = 1;

    const Admin = 2;

    private $id;

    private $first;

    private $last;

}

?>