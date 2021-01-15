<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReaderWriterController extends Controller
{
    private $dir, $fileReader,  $fileWriter;

    public function __construct()
    {
        
        $this->fileReader = null;
        $this->fileWritter = null;
    }

    // ambil dari index 2
    public function bacaNamaFile($mode)//1 dusk, 2 php unit
    {
        if($mode == 1){
            $this->dir = "..\\GherkinDusk\\";
        }else{
            $this->dir = "..\\GherkinPHPUnit\\";
        }
        $namaFile = [];
        if (is_dir($this->dir)) {
            if ($dh = opendir($this->dir)) {
                while (($file = readdir($dh)) !== false) {
                    array_push($namaFile, $file);
                }
                closedir($dh);
            }
        }
        return $namaFile;
    }

    public function bacaNamaModel(){
        $dir = "..\\app\\";
        $namaModel = [];
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    array_push($namaModel, $file);
                }
                closedir($dh);
            }
        }
        return $namaModel;
    }

    public function bacaFileHeader()
    {
        $header = [];
        $words = preg_split('/\s+/', fgets($this->fileReader), -1, PREG_SPLIT_NO_EMPTY);
        $folderName = $words[1];
        array_push($header, $folderName);
        $newFileName = $words[2];
        array_push($header, $newFileName);
        $namaModel = $words[3];
        array_push($header, $namaModel);

        return $header;
    }

    public function setFileReader($namaFile)
    {
        $this->fileReader = fopen($this->dir . $namaFile, 'r');
    }

    public function getFileReader()
    {
        return $this->fileReader;
    }

    public function buatFolder($newDir, $folderName, $fileName)
    {
        $dir = dirname(__DIR__) . $newDir . $folderName;

        if (is_dir($dir) === false) {
            mkdir($dir);
        }


        $this->setFileWriter($dir, $fileName);
    }

    public function setFileWriter($dir, $fileName)
    {
        $this->fileWriter = fopen($dir . '/' . $fileName . 'Test.php', 'w');
    }

    public function getFileWriter()
    {
        return $this->fileWriter;
    }
}
