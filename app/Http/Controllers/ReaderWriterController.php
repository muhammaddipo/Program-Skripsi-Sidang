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
        $namaFile = []; //array yang akan berisi semua nama file dari folder GherkinDusk atau GherkinPHPUnit
        if (is_dir($this->dir)) {//Cek kalau path sesuai Dusk atau PHPUnit
            if ($dh = opendir($this->dir)) {//Masuk ke folder
                while (($file = readdir($dh)) !== false) {//Selama nama file masih ada/tidak kosong dalam folder
                    array_push($namaFile, $file);//Masukan nama file didalam folder(satu per satu) kedalam array namaFile
                }
                closedir($dh);
            }
        }
        return $namaFile;
    }

    public function bacaNamaModel(){
        $dir = "..\\app\\";//Folder model
        $namaModel = [];//Array untuk menampung nama model dari fitur-fitur website
        if (is_dir($dir)) {//Cek kalau path sudah sesuai untuk mengambil nama model
            if ($dh = opendir($dir)) {//Masuk ke folder
                while (($file = readdir($dh)) !== false) {//Baca semua isi dari folder app sampai file terakhir
                    array_push($namaModel, $file);//Masukan nama file kedalam array namaModel
                }
                closedir($dh);
            }
        }
        return $namaModel;
    }

    public function bacaFileHeader()
    {
        $header = [];//Array untuk menampung header(baris pertama pada skenario) dari skenario Gherkin 
        $words = preg_split('/\s+/', fgets($this->fileReader), -1, PREG_SPLIT_NO_EMPTY);//Membaca baris pertama dari skenario Gherkin
        $folderName = $words[1];//Untuk nama folder sebagai penempatan file testing
        array_push($header, $folderName);
        $newFileName = $words[2];//Untuk nama file testing
        array_push($header, $newFileName);
        $namaModel = $words[3];//Nama model yang fiturnya akan diuji dari skenario Gherkin
        array_push($header, $namaModel);

        return $header;
    }

    public function setFileReader($namaFile)
    {
        $this->fileReader = fopen($this->dir . $namaFile, 'r');//Membaca file skenario Gherkin
    }

    public function getFileReader()
    {
        return $this->fileReader;//Dapetin file skenario Gherkin
    }

    public function buatFolder($newDir, $folderName, $fileName)
    {
        $dir = dirname(__DIR__) . $newDir . $folderName;//Path untuk penempatan file testing

        if (is_dir($dir) === false) {//Cek jika nama folder sudah ada
            mkdir($dir);
        }


        $this->setFileWriter($dir, $fileName);//File testing yang akan ditulis kode.
    }

    public function setFileWriter($dir, $fileName)
    {
        $this->fileWriter = fopen($dir . '/' . $fileName . 'Test.php', 'w');//Bikin file baru untuk menulis kode testing
    }

    public function getFileWriter()
    {
        return $this->fileWriter;//Mendapatkan file testing yang akan ditulis kode testing
    }
}
