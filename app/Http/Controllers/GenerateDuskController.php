<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class GenerateDuskController extends Controller
{
    private $newDir, $fileWriter;

    public function __construct()
    {
        $this->newDir = '..\\..\\..\\tests\\Browser\\';//Folder penempatan file Dusk
    }

    public function setFileWriter($fileWriter)
    {
        $this->fileWriter = $fileWriter;//Menentukan file yang akan dituliskan kode Dusk
    }

    private function write($text)
    {
        fwrite($this->fileWriter, $text);//Menulis kode kedalam file Dusk
    }

    public function getNewDir()
    {
        return $this->newDir;//Mendapatkan folder penempatan file Dusk
    }

    public function writeBody($newFileName, $fileReader, $namaModel, $namaModels)//Menuliskan kode kedalam file Dusk
    {
        //header body
        $this->write("<?php\n");
        $this->write("namespace Tests\Browser;\n \n");

        $this->write("use Tests\DuskTestCase;\n");//Extend parent package Dusk
        $this->write("use Laravel\Dusk\Browser;\n");//Untuk menjalankan Dusk browser
        $this->write("use Illuminate\Foundation\Testing\DatabaseMigrations;\n \n");//Migrasi database untuk data pada website

        $this->write("class " . $newFileName . "Test" . " extends DuskTestCase { \n \n");//Bikin class dengan nama file baru


        // Variable array keys yang berisi kata kunci sebagai penanda untuk kode apa yang seharusnya ditulis jika kata kunci tersebut muncul
        $keys = [
            "Scenario:", "Given", "When", "And", "Then", "halaman", "tombol", "berhasil", "tulisan", //8
            "login", "menggunakan", "link", "opsi", "atribut", "melampirkan", "memilih", "password_confirmation", //16
            "menunggu", "klik", "tetap", "tgl" //20
        ];

        //keys 9, 10, 15


        // $pathModel = "App\\" . $namaModel;
        // $model = new $pathModel;
        // $fillable = $model->getFillable();

        $banyakTest = 1;//Banyaknya test yang dilakukan

        //$used = []; Tidak dipakai karena model sudah panggil semua

        if ($fileReader) {//Cek jika file reader terisi akan menulis kode sesuai kondisi keyword
            while (($line = fgets($fileReader)) !== false) {//Membaca file skenario per baris
                $words = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);//Memecah baris menjadi kata per kata

                // idx untuk words
                for ($i = 0; $i < sizeof($words); $i++) {//Mencari keyword dari kata-kata yang sudah dipecah dari file skenario Gherkin
                    if ($words[$i] == $keys[0]) { // Scenario:
                        $this->write("public function testUnit" . $banyakTest . "(){\n \t");//Buat fungsi dengan nama testUnit ditambah banyaknya test
                        $this->write('$this->browse(function (Browser $browser)' . "{\n \t");//Memanggil browser

                        $banyakTest = $banyakTest + 1;
                        //$status = $words[$i + 2];
                        //$used = [];
                    } else if ($words[$i] == $keys[1]) { // Given
                        for ($j = 0; $j < sizeof($words); $j++) {
                            if ($words[$j] == $keys[5]) { // halaman
                                $this->write('$browser->visit(' . "'/" . $words[$j + 1] . "') \n \t");//Menandakan pengguna berada dihalaman apa sebelum melakukan aksi
                            }
                        }
                    } else if ($words[$i] == $keys[2] || $words[$i] == $keys[3]) { // When & And
                        for ($j = 0; $j < sizeof($words); $j++) {//Membaca kata per kata dalam satu baris untuk mencari keyword
                            foreach ($namaModels as $model1) {//Mendapatkan nama model yang ada
                                if (str_contains($model1, ".php") && !str_contains($model1, "Helpers")) {//Jika nama file diakhiri php dan bukan Helpers maka file tersebut ialah file model
                                    $pathModel = "App\\" . substr($model1, 0, -4);//Supaya .php tidak terbawa

                                    $model = new $pathModel;//Inisialisasi kelas model
                                    $fillable = $model->getFillable();//Mendapatkan atribut yang terdapat didalam model untuk dicek apakah atribut tersebut ada didalam skenario

                                    foreach ($fillable as $atr) {//Pengulangan untuk mendapatkan atribut model

                                        if ($words[$j] == $atr) {//Mengecek apakah atribut pada model digunakan pada skenario Gherkin
                                            // if (in_array($words[$j], $used) == false) {
                                            if ($words[$j - 1] == $keys[12]) { //opsi
                                                $this->write("->select('" . $words[$j] . "','" . $words[sizeof($words) - 1] . "')\n \t");
                                            } else if ($words[$j - 1] == $keys[14]) { //melampirkan
                                                $this->write("->attach('" . $words[$j] . "',base_path('public/images/" . strtolower($namaModel) . "/" . $words[sizeof($words) - 1] . ".png'))\n \t");
                                            } else if (str_contains($words[$j], $keys[20])) { //kalau ada tgl
                                                $this->write("->keys('#" . $words[$j] . "','" . $words[$j + 2] . "')\n \t");
                                            } else {//Untuk mengatasi kasus spasi pada keyword mengisi

                                                $str = "";
                                                for ($k = $j + 2; $k < sizeof($words); $k++) {
                                                    $str .= $words[$k];
                                                    if ($k != sizeof($words) - 1) {
                                                        $str .= " ";
                                                    }
                                                }
                                                $this->write("->type('" . $words[$j] . "', '" . $str . "') \n \t");
                                            }
                                            //array_push($used, $words[$j]);
                                            // }

                                            // $this->write($atr." \n");
                                        }
                                    }
                                }
                            }

                            // if (in_array($keys[16], $used) && in_array($keys[17], $used)) {
                            //     if (in_array($keys[20], $used)) {
                            //         if ($namaModel == $keys[19]) { //User dan sudah login
                            //             if ($words[$j] == $keys[16] || $words[$j] == $keys[17]) { // email, password, 
                            //                 $this->write("->type('" . $words[$j] . "', '" . $words[$j + 2] . "') \n \t");
                            //             }
                            //         }
                            //     }
                            //     array_push($used, $keys[20]);
                            // }

                            // if (in_array($words[$j], $used) == false) { //untuk email dan password jika tidak ada di model
                            //     if ($words[$j] == $keys[16] || $words[$j] == $keys[17]) { //16 email //17 password
                            //         $this->write("->type('" . $words[$j] . "', '" . $words[$j + 2] . "') \n \t");
                            //         array_push($used, $words[$j]);
                            //     }
                            // }

                            if ($words[$j] == $keys[6]) { //tombol
                                if (isset($words[$j + 2])) {
                                    $this->write("->press('" . $words[$j + 1] . " " . $words[$j + 2] . "')\n \t");
                                } else {
                                    $this->write("->press('" . $words[$j + 1] . "')\n \t");
                                }
                            } else if ($words[$j] == $keys[11]) { //link
                                $this->write("->clickLink('" . $words[$j + 1] . " " . $words[$j + 2] . "')\n \t");
                            } else if ($words[$j] == $keys[12]) { //opsi
                                // $this->write("->select('" . $words[$j + 1] . "','" . $words[$j + 2] . "')\n \t");
                            } else if ($words[$j] == $keys[14]) { //melampirkan
                                // $this->write("->attach('" . $words[$j + 1] . "',base_path('public/images/" . strtolower($namaModel) . "/" . $words[sizeof($words) - 1] . "png'))\n \t");
                            } else if ($words[$j] == $keys[16]) { //password_confirmation
                                $this->write("->type('" . $words[$j] . "', '" . $words[$j + 2] . "') \n \t");
                            }
                            //  else if ($words[$j] == $keys[21]) { //jenis kelamin
                            //     $this->write("->select('" . $words[$j] . "','" . $words[$j + 2] . "')\n \t");
                            // } 
                            else if ($words[$j] == $keys[17]) { //menunggu
                                $this->write("->waitForText('" . $words[$j + 3] . " " . $words[$j + 4] . "')\n \t");
                            } else if ($words[$j] == $keys[18]) { //klik
                                $this->write('->click("tr[data-' . $words[$j + 2] . '_' . $words[$j + 1] . "='");

                                for ($k = $j + 3; $k < sizeof($words); $k++) {
                                    $this->write($words[$k]);
                                    if ($k != sizeof($words) - 1) {
                                        $this->write(" ");
                                    }
                                }
                                $j++; //agar judul dan nama di supaya tidak bentrok dengan atribut judul dan tidak masuk lagi ke heasil generate kode 
                                $this->write("']" . '"' . ")\n\t");
                            }
                            // else if ($words[$j] == $keys[24]) { //keterangan
                            //     $this->write("->type('" . $words[$j] . "', '" . $words[$j + 1] . "') \n \t");
                            // }
                        }
                    } else if ($words[$i] == $keys[4]) { //Then
                        for ($j = 0; $j < sizeof($words); $j++) {
                            if ($words[$j] == $keys[7] || $words[$j] == $keys[19]) { //berhasil || tetap
                                $this->write("->assertPathIs('/" . $words[sizeof($words) - 1] . "'); \n \t}); \n} \n \n");
                            } else if ($words[$j] == $keys[8]) { //tulisan
                                $msg = "";
                                for ($k = $j + 1; $k < sizeof($words); $k++) {//Digunakan awalnya ketika login gagal akan memunculkan kalimat yang memiliki spasi
                                    $msg .= $words[$k];
                                    if ($k != sizeof($words) - 1) {
                                        $msg .= " ";
                                    }
                                }
                                $this->write("->assertSee(" . $msg . "); \n \t}); \n} \n \n");
                            } else if ($words[$j] == $keys[13]) { //atribut
                                $this->write(";});\n \t");
                                $this->write('$this' . "->assertDatabaseHas('" . $words[sizeof($words) - 1] . "',[ \n \t");
                                $this->write("'" . $words[$j + 1] . "' => " . "'" . $words[$j + 2] . "'\n\t");
                                $this->write("]);\n \t \n} \n \n");
                            }
                        }
                    }
                }
            }
        }
        $this->write("}");
    }
}
