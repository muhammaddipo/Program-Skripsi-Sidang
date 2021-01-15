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
        $this->namaModels = $this->readerWriter->bacaNamaModel();
    }

    public function generateDusk()
    {
        $namaFile = $this->readerWriter->bacaNamaFile(1);

        $generateDusk = new GenerateDuskController();

        // loop untuk semua nama file
        for ($i = 2; $i < sizeof($namaFile); $i++) {
            $file = $namaFile[$i];
            // $file="transaksi.txt";

            // set file reader
            $this->readerWriter->setFileReader($file);
            // get file reader
            $fileReader = $this->readerWriter->getFileReader();


            // membaca header
            $header = $this->readerWriter->bacaFileHeader();

            $folderName = $header[0];
            $newFileName = $header[1];
            $namaModel = $header[2];

            // buat folder + set file writer di readerWriter
            $newDir = $generateDusk->getNewDir();
            $this->readerWriter->buatFolder($newDir, $folderName, $newFileName);

            $fileWriter = $this->readerWriter->getFileWriter();
            $generateDusk->setFileWriter($fileWriter);

            // bikin body
            $generateDusk->writeBody($newFileName, $fileReader, $namaModel, $this->namaModels);
        }
    }

    public function generatePHPUnit()
    {
        $namaFile = $this->readerWriter->bacaNamaFile(2);

        $generatePHPUnit = new GeneratePHPUnitController();

        // loop untuk semua nama file
        for ($i = 2; $i < sizeof($namaFile); $i++) {
            $file = $namaFile[$i];
            // $file = "transaksi.txt";

            // set file reader
            $this->readerWriter->setFileReader($file);
            // get file reader
            $fileReader = $this->readerWriter->getFileReader();

            // membaca header
            $header = $this->readerWriter->bacaFileHeader();

            $folderName = $header[0];
            $newFileName = $header[1];
            $namaModel = $header[2];

            // buat folder + set file writer di readerWriter
            $newDir = $generatePHPUnit->getNewDir();
            $this->readerWriter->buatFolder($newDir, $folderName, $newFileName);

            $fileWriter = $this->readerWriter->getFileWriter();
            $generatePHPUnit->setFileWriter($fileWriter);

            // bikin body
            $generatePHPUnit->writeBody($newFileName, $fileReader, $namaModel, $folderName, $this->namaModels);

        }
    }

    public function mode(Request $request)
    {
        $btn = $request['btn'];
        if ($btn == 'dusk') {
            $this->generateDusk();
        } else {
            $this->generatePHPUnit();
        }
        return view("generate", ["dir" => $request['btn']]);
    }
}
