<?php
function getDbConnection() {
    $link = new SQLite3('blogsite.sqlite');
    if (false === $link) {
        http_response_code(500);
        exit('Datebase connection error!');
    }
    return $link;
}
