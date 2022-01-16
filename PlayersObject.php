<?php

/*
    Development Exercise

      The following code is poorly designed and error prone. Refactor the objects below to follow a more SOLID design.
      Keep in mind the fundamentals of MVVM/MVC and Single-responsibility when refactoring.

      Further, the refactored code should be flexible enough to easily allow the addition of different display
        methods, as well as additional read and write methods.

      Feel free to add as many additional classes and interfaces as you see fit.

      Note: Please create a fork of the https://github.com/BrandonLegault/exercise repository and commit your changes
        to your fork. The goal here is not 100% correctness, but instead a glimpse into how you
        approach refactoring/redesigning bad code. Commit often to your fork.

*/

interface IWritePlayers {
    function write($player);
}

interface IWritePlayersToFile {
    function write(IGetPLayersFromFile $getterObj, $player, $filename);
}

interface IDisplayPlayers {
    function display($players);
}

interface IGetPlayers {
    function get();
}

interface IGetPLayersFromFile {
    function get($filename);
}

interface IFormatData{
    function formatData($data);
}


class WritePlayerJson implements IWritePlayers{
    private $playerJsonString;

    public function __construct() {
        $this->playerJsonString = null;
    }

    function write($player){
        $players = [];
        if ($this->playerJsonString) {
            $players = json_decode($this->playerJsonString);
        }
        $players[] = $player;
        $this->playerJsonString = json_encode($player);
    }
}

class WritePlayerArray implements IWritePlayers{
    private $playersArray;

    public function __construct() {
        $this->playersArray = [];
    }

    function write($player){
        $this->playersArray[] = $player;
    }
}

class WritePlayerFile implements IWritePlayersToFile{
    function write(IGetPLayersFromFile $getterObj, $player, $filename){
        $players = $getterObj->get($filename);
        if (!$players) {
            $players = [];
        }
        $players[] = $player;
        file_put_contents($filename, json_encode($players));
    }
}


class GetPlayersFromFile implements IGetPLayersFromFile, IFormatData{
    function get($filename) {
        $file = file_get_contents($filename);
        $file = $this->formatData($file);

        return $file;
    }

    function formatData($data){
        if (is_string($data)) {
            return json_decode($data);
        }
        return $data;
    }
}

class GetPlayersFromArray implements IGetPlayers{
    function get() {

        $players = [];

        $jonas = new \stdClass();
        $jonas->name = 'Jonas Valenciunas';
        $jonas->age = 26;
        $jonas->job = 'Center';
        $jonas->salary = '4.66m';
        $players[] = $jonas;

        $kyle = new \stdClass();
        $kyle->name = 'Kyle Lowry';
        $kyle->age = 32;
        $kyle->job = 'Point Guard';
        $kyle->salary = '28.7m';
        $players[] = $kyle;

        $demar = new \stdClass();
        $demar->name = 'Demar DeRozan';
        $demar->age = 28;
        $demar->job = 'Shooting Guard';
        $demar->salary = '26.54m';
        $players[] = $demar;

        $jakob = new \stdClass();
        $jakob->name = 'Jakob Poeltl';
        $jakob->age = 22;
        $jakob->job = 'Center';
        $jakob->salary = '2.704m';
        $players[] = $jakob;

        return $players;

    }
}

class GetPlayersFromJson implements IGetPlayers, IFormatData{
     function get() {
        $json = '[{"name":"Jonas Valenciunas","age":26,"job":"Center","salary":"4.66m"},{"name":"Kyle Lowry","age":32,"job":"Point Guard","salary":"28.7m"},{"name":"Demar DeRozan","age":28,"job":"Shooting Guard","salary":"26.54m"},{"name":"Jakob Poeltl","age":22,"job":"Center","salary":"2.704m"}]';
        $formattedJson = $this->formatData($json);

        return $formattedJson;
    }

    function formatData($data){
        if (is_string($data)) {
            return json_decode($data);
        }
        return $data;
    }
}

class DisplayPlayersCLI implements IDisplayPlayers{
    function display($players){
    echo "Current Players: \n";
    foreach ($players as $player) {

        echo "\tName: $player->name\n";
        echo "\tAge: $player->age\n";
        echo "\tSalary: $player->salary\n";
        echo "\tJob: $player->job\n\n";
        }
    }
}


class DisplayPlayersNotCLI implements IDisplayPlayers{
    function display($players) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    li {
                        list-style-type: none;
                        margin-bottom: 1em;
                    }
                    span {
                        display: block;
                    }
                </style>
            </head>
            <body>
            <div>
                <span class="title">Current Players</span>
                <ul>
                    <?php foreach($players as $player) { ?>
                        <li>
                            <div>
                                <span class="player-name">Name: <?= $player->name ?></span>
                                <span class="player-age">Age: <?= $player->age ?></span>
                                <span class="player-salary">Salary: <?= $player->salary ?></span>
                                <span class="player-job">Job: <?= $player->job ?></span>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </body>
            </html>
            <?php
        }
}

//////////////////////////////////////
// Testing section
//////////////////////////////////////

$rea = new \stdClass();
$rea->name = 'Rea';
$rea->age = 100;
$rea->job = 'Poker Player';
$rea->salary = '20M';


// ARRAY TEST
class ArrayGenerator{
    function write($player){
        $arr = new WritePlayerArray();
        $arr->write($player);
        var_dump($arr);
    }

    function get(){
        $arr = new GetPlayersFromArray();
        $arr = $arr->get();
        echo json_encode($arr);
    }

    function displayCli(){
        $arr = new GetPlayersFromArray();
        $players = $arr->get();

        $arr = new DisplayPlayersCLI();
        $arr = $arr->display($players);
    }

    function displayNotCli(){
        $arr = new GetPlayersFromArray();
        $players = $arr->get();

        $arr = new DisplayPlayersNotCLI();
        $arr = $arr->display($players);
    }
}

// $a = new ArrayGenerator();
// $a->get();
// $a->displayCli();
// $a->displayNotCli();
// $a->write($rea);


// JSON TEST
class JSONGenerator{
    function write($player){
        $arr = new WritePlayerJson();
        $arr->write($player);
        var_dump($arr);
    }

    function get(){
        $arr = new GetPlayersFromJson();
        $arr = $arr->get();
        echo json_encode($arr);
    }

    function displayCli(){
        $arr = new GetPlayersFromJson();
        $players = $arr->get();

        $arr = new DisplayPlayersCLI();
        $arr = $arr->display($players);
    }

    function displayNotCli(){
        $arr = new GetPlayersFromJson();
        $players = $arr->get();

        $arr = new DisplayPlayersNotCLI();
        $arr = $arr->display($players);
    }
}

// $a = new JSONGenerator();
// $a->get();
// $a->displayCli();
// $a->displayNotCli();
// $a->write($rea);


// File TEST
class FileGenerator{
    function write($player){
        $getObj = new GetPlayersFromFile();

        $arr = new WritePlayerFile();
        $arr->write($getObj, $player, 'test.txt');
    }

    function get(){
        $arr = new GetPlayersFromFile();
        $arr = $arr->get('test.txt');
        echo ($arr);
    }

    function displayCli(){
        $arr = new GetPlayersFromFile();
        $players = $arr->get('test.txt');

        $arr = new DisplayPlayersCLI();
        $arr = $arr->display($players);
    }

    function displayNotCli(){
        $arr = new GetPlayersFromJson();
        $players = $arr->get('test.txt');

        $arr = new DisplayPlayersNotCLI();
        $arr = $arr->display($players);
    }
}

// $a = new FileGenerator();
// $a->get();
// $a->displayCli();
// $a->displayNotCli();
// $a->write($rea);
?>