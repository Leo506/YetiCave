<?php
const HOST = 'localhost';

//const LOGIN = 'zinevftp';
const LOGIN = 'root';

//const PASSWORD = 'm1G7Hk';
const PASSWORD = 'password';

const DATABASE = "zinevftp_m3";

$con = mysqli_connect(HOST, LOGIN, PASSWORD, DATABASE);

mysqli_set_charset($con, "utf8");