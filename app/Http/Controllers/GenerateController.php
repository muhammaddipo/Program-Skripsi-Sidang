<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use SebastianBergmann\Environment\Console;
use Svg\Tag\Rect;

class GenerateController extends Controller
{
    private $readerWriter, $namaModels;

    public function __construct()
    {
        $this->readerWriter = new ReaderWriterController();
        $this->namaModels = $this->readerWriter->bacaNamaModel();//Nama model tiap fitur
    }

    public function generateDusk()
    {
        $namaFile = $this->readerWriter->bacaNamaFile(1);//Array yang berisi seluruh nama file dalam folder GherkinDusk

        $generateDusk = new GenerateDuskController();

        // loop untuk semua nama file
        for ($i = 2; $i < sizeof($namaFile); $i++) {//Kenapa mulai dari 2, karena keluar dari folder sebanyak 2 kali untuk masuk ke folder GherkinDusk
            $file = $namaFile[$i];
            // $file="transaksi.txt";

            // set file reader
            $this->readerWriter->setFileReader($file);//Menentukan file mana yang akan dibaca
            // get file reader
            $fileReader = $this->readerWriter->getFileReader();//Mendapatkan file yang ingin dibaca


            // membaca header
            $header = $this->readerWriter->bacaFileHeader();//Mendapatkan header dari file yang dibaca

            $folderName = $header[0];//Untuk nama folder sebagai penempatan file Dusk
            $newFileName = $header[1];//Untuk nama file Dusk
            $namaModel = $header[2];//Nama model yang fiturnya akan diuji dari skenario Gherkin

            // buat folder + set file writer di readerWriter
            $newDir = $generateDusk->getNewDir();//Penempatan folder yang menampung file Dusk
            $this->readerWriter->buatFolder($newDir, $folderName, $newFileName);//Bikin folder dan set nama file Dusk.

            $fileWriter = $this->readerWriter->getFileWriter();//Mendapatkan file yang akan dituliskan kode Dusk
            $generateDusk->setFileWriter($fileWriter);//Set file yang akan dituliskan kode pada kelas GenerateDuskController

            // bikin body
            $generateDusk->writeBody($newFileName, $fileReader, $namaModel, $this->namaModels);//Menulis kode Dusk berdasarkan skenario Gherkin pada folder dan file yang sudah ditentukan.
        }
    }

    public function generatePHPUnit()
    {
        $namaFile = $this->readerWriter->bacaNamaFile(2);//Array yang berisi seluruh nama file dalam folder GherkinPHPUnit

        $generatePHPUnit = new GeneratePHPUnitController();

        // loop untuk semua nama file
        for ($i = 2; $i < sizeof($namaFile); $i++) {//Kenapa mulai dari 2, karena keluar dari folder sebanyak 2 kali untuk masuk ke folder GherkinPHPUnit
            $file = $namaFile[$i];
            // $file = "transaksi.txt";

            // set file reader
            $this->readerWriter->setFileReader($file);//Menentukan file mana yang akan dibaca
            // get file reader
            $fileReader = $this->readerWriter->getFileReader();//Mendapatkan file yang ingin dibaca

            // membaca header
            $header = $this->readerWriter->bacaFileHeader();//Mendapatkan header dari file yang dibaca

            $folderName = $header[0];//Untuk nama folder sebagai penempatan file PHPUnit
            $newFileName = $header[1];//Untuk nama file PHPUnit
            $namaModel = $header[2];//Nama model yang fiturnya akan diuji dari skenario Gherkin

            // buat folder + set file writer di readerWriter
            $newDir = $generatePHPUnit->getNewDir();//Penempatan folder yang menampung file Dusk
            $this->readerWriter->buatFolder($newDir, $folderName, $newFileName);//Bikin folder dan set nama file Dusk.

            $fileWriter = $this->readerWriter->getFileWriter();//Mendapatkan file yang akan dituliskan kode Dusk
            $generatePHPUnit->setFileWriter($fileWriter);//Set file yang akan dituliskan kode pada kelas GenerateDuskController

            // bikin body
            $generatePHPUnit->writeBody($newFileName, $fileReader, $namaModel, $folderName, $this->namaModels);//Menulis kode Dusk berdasarkan skenario Gherkin pada folder dan file yang sudah ditentukan.

        }
    }

    public function mode(Request $request)
    {
        $btn = $request['btn'];//Nerima input dari antarmuka, tombol yang ditekan itu apakah tombol dusk/phpunit
        if ($btn == 'dusk') {
            $this->generateDusk();
        } else {
            $this->generatePHPUnit();
        }
        return view("generate", ["dir" => $request['btn']]);//Menampilkan UI dari file generate, dan melihat tombol apa yang ditekan.
    }
}
