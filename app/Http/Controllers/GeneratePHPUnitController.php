<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class GeneratePHPUnitController extends Controller
{
    private $newDir, $fileWriter;

    public function __construct()
    {
        $this->newDir = '..\\..\\..\\tests\\Feature\\';//Folder penempatan file PHPUnit
    }

    public function setFileWriter($fileWriter)
    {
        $this->fileWriter = $fileWriter;//Menentukan file yang akan dituliskan kode PHPUnit
    }

    private function write($text)
    {
        fwrite($this->fileWriter, $text);//Menulis kode kedalam file PHPUnit
    }

    public function getNewDir()
    {
        return $this->newDir;//Mendapatkan folder penempatan file PHPUnit
    }

    public function writeBody($newFileName, $fileReader, $namaModel, $namaFolder, $namaModels)
    {
        // Variable array keys yang berisi kata kunci sebagai penanda untuk kode apa yang seharusnya ditulis jika kata kunci tersebut muncul
        $keys = [
            "Scenario:", "Given", "When", "And", "Then", "halaman", "Login", //6
            "berhasil", "tulisan", "Sign", "kembali", "Submit", "atribut", //12
            "Register", "gambar", "tgl", "tetap" // 16
        ];

        $this->write("<?php\n");
        $this->write("namespace Tests\\Feature\\" . $namaFolder . ";\n \n");

        $this->write("use Tests\TestCase;\n");//Extend parent package PHPUnit
        $this->write("use Illuminate\Foundation\Testing\WithFaker;\n");//Untuk generate string random(tidak dipakai)
        $this->write("use Illuminate\Foundation\Testing\RefreshDatabase;\n \n");//Refresh database setiap melakukan testing

        $pathModel = "App\\" . $namaModel;//Menampung path model
        $model = new $pathModel;//Inisialisasi kelas model
        // $fillable = $model->getFillable();

        $pathController =  "App\\Http\\Controllers\\" . $namaModel . "Controller";//Path controller
        //$controller = new $pathController;

        $this->write("use " . $pathModel . ";\n \n");//Manggil model
        $this->write("use " . $pathController . ";\n \n");//Manggil controller

        // gunakan semua model yang ada
        // foreach ($namaModels as $model) {
        //     if (str_contains($model, ".php") && !str_contains($model, "Helpers")) {
        //         $pathModel = "App\\" . substr($model, 0, -4);
        //         $this->write("use " . $pathModel . ";\n \n");
        //     }
        // }

        $this->write("class " . $newFileName . "Test" . " extends TestCase { \n \n");//Membuat kelas dengan nama file baru

        $banyakTest = 1;//Banyaknya test yang dilakukan

        $used = []; // Array ini berisi atribut pada model yang digunakan pada skenario gherkin yang sudah dibuat
        $array = []; // Digunakan untuk menyimpan key dan value dari model sesuai atribut yang di terdapat pada array used
        $logout = false; //Cek sudah logout atau belum

        if ($fileReader) {//Cek jika file reader terisi akan menulis kode sesuai kondisi keyword
            while (($line = fgets($fileReader)) !== false) {//Membaca file skenario per baris
                $words = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);//Memecah baris menjadi kata per kata

                // idx untuk words
                for ($i = 0; $i < sizeof($words); $i++) {//Mencari keyword dari kata-kata yang sudah dipecah dari file skenario Gherkin

                    if ($words[$i] == $keys[0]) { // Scenario:
                        $this->write("public function testUnit" . $banyakTest . "(){\n \t");//Buat fungsi dengan nama testUnit ditambah banyaknya test
                        $banyakTest++;
                    } else if ($words[$i] == $keys[1] || $words[$i] == $keys[2] || $words[$i] == $keys[3]) { //Given || When || And
                        for ($j = 0; $j < sizeof($words); $j++) {//Membaca kata per kata dalam satu baris untuk mencari keyword

                            // untuk given
                            if ($words[$j] == $keys[5]) { //halaman
                                $this->write('$response = $this->post(' . "'/" . $words[$j + 1] . "',[\n\t");//Pengguna memerlukan method post untuk mengirimkan informasi email dan password saat login
                            }

                            // cek semua atribut yang ada di db
                            foreach ($namaModels as $model1) {//Mendapatkan nama model yang ada
                                if (str_contains($model1, ".php") && !str_contains($model1, "Helpers")) {//Jika nama file diakhiri php dan bukan Helpers maka file tersebut ialah file model
                                    $pathModel = "App\\" . substr($model1, 0, -4);//Supaya .php tidak terbawa

                                    $model = new $pathModel;//Inisialisasi kelas model
                                    $fillable = $model->getFillable();//Mendapatkan atribut yang terdapat didalam model untuk dicek apakah atribut tersebut ada didalam skenario
                                    foreach ($fillable as $atr) {//Pengulangan untuk mendapatkan atribut model
                                        if ($words[$j] == $atr) {//Mengecek apakah atribut pada model digunakan pada skenario Gherkin
                                            if (in_array($words[$j], $used) == false) {//Kalau didalam array used belum terdapat atribut yang didapatkan dalam pengecekan
                                                $key = $words[$j];//Atribut model
                                                $array[$key] = [];//Inisialisasi array associative dengan atribut dijadikan sebagai key, dan nilainya sebagai value
                                                $str = "";//Value dari atribut
                                                for ($k = $j + 2; $k < sizeof($words); $k++) {
                                                    $str .= $words[$k];
                                                    if ($k != sizeof($words) - 1) {
                                                        $str .= " ";
                                                    }
                                                }

                                                array_push($used, $words[$j]);//Memasukan atribut yang didapat pada skenario gherkin kedalam array used
                                                array_push($array[$key], $str);//Memasukan value kedalam array yang keynya berupa atribut pada model
                                            }
                                        }
                                    }
                                }
                            }


                            // if (in_array($words[$j], $used) == false) { //untuk nama yang tidak ada di db transaksi
                            //     if ($words[$j] == $keys[22]) { //11 email //12 password 22 nama
                            //         $key = $words[$j];
                            //         $array[$key] = [];

                            //         array_push($used, $words[$j]);
                            //         array_push($array[$key], $words[$j + 2]);
                            //     }
                            // }

                            if ($words[$j] == $keys[6]) { //login
                                foreach ($array as $key => $value) {
                                    $this->write("'" . $key . "'=>'" . $value[0] . "',\n\t");
                                }
                                $this->write("]);\n\t");
                                $array = [];
                                $used = [];
                            } else if ($words[$j] == $keys[9]) { // Sign -> Signout
                                $this->write('$response = $this->post(' . "'/logout');\n\t");
                                $logout = true;
                            } else if ($words[$j] == $keys[11] || $words[$j] == $keys[13]) { // 11 submit 13 Register
                                $arrKey = array_keys($array);
                                $this->write('$count = ' . $namaModel . "::where('" . $arrKey[0] . "','" . $array[$arrKey[0]][0] . "')->count();\n\t");

                                // if ($namaModel == $keys[17]) { //user
                                //     $atrNama = "name";
                                // } else if ($namaModel == $keys[18]) {
                                //     $atrNama = "nama";
                                // }

                                // if ($namaModel == $keys[17] || $namaModel == $keys[18]) { // 17 User 18 Anggota
                                //     $this->write('$count = ' . $namaModel . "::where('" . $atrNama . "','" . $array[$atrNama][0] . "')->count();\n\t");
                                // } else if ($namaModel == $keys[20]) { // buku
                                //     $this->write('$count = ' . $namaModel . "::where('isbn','" . $array['isbn'][0] . "')->count();\n\t");
                                // } else if ($namaModel == $keys[21]) { // transaksi
                                //     $this->write('$count = ' . $namaModel . "::where('anggota_id'," . $array['anggota_id'][0] . ")->count();\n\t");
                                // }

                                $this->write('$array1 = [' . "\n\t");

                                foreach ($array as $key => $value) {
                                    if ($key == $keys[14]) { //gambar
                                        $this->write("'" . $key . "'=>NULL,\n\t");
                                    } else if (str_contains($key, $keys[15])) { //kalau ada tgl
                                        $tgl = substr($value[0], 4, 4) . "-" . substr($value[0], 0, 2) . "-" . substr($value[0], 2, 2); // dd/mm/yyyy -> yyyy-mm-dd
                                        $this->write("'" . $key . "'=>'" . $tgl . "',\n\t");
                                    } else {
                                        $this->write("'" . $key . "'=>'" . $value[0] . "',\n\t");
                                    }
                                }
                                $this->write('];' . "\n\t");
                                $this->write('$controller = new ' . $namaModel . "Controller();\n\t");

                                $this->write('if($count==0){' . "\n\t\t");


                                $this->write('$controller->storeFunction' . '($array1, $gambar=NULL)' . ";\n\t");
                                $this->write("}\n\t");


                                // if ($namaModel == $keys[17] || $namaModel == $keys[18]) { // 17 User 18 Anggota
                                //     $this->write('$newCount = ' . $namaModel . "::where('" . $atrNama . "','" . $array[$atrNama][0] . "')->count();\n\t");
                                // } else if ($namaModel == $keys[20]) { // buku
                                //     $this->write('$newCount = ' . $namaModel . "::where('isbn','" . $array['isbn'][0] . "')->count();\n\t");
                                // } else if ($namaModel == $keys[21]) { // transaksi
                                //     $this->write('$newCount = ' . $namaModel . "::where('anggota_id'," . $array['anggota_id'][0] . ")->count();\n\t");
                                // }
                            }
                        }
                    } else if ($words[$i] == $keys[4]) { //Then
                        for ($j = 0; $j < sizeof($words); $j++) {
                            if ($words[$j] == $keys[7]) { // berhasil
                                if ($logout) {
                                    $this->write('$response->assertRedirect(' . "''" . "); \n\t} \n\t \n\t");
                                } else {
                                    $this->write('$response->assertRedirect(' . "'/" . $words[sizeof($words) - 1] . "'" . "); \n\t} \n\t \n\t");
                                }
                            } else if ($words[$j] == $keys[10] || $words[$j] == $keys[16]) { // 10 kembali 16 tetap
                                $this->write('$response->assertRedirect(' . "''" . "); \n\t} \n\t \n\t");
                            } else if ($words[$j] == $keys[12]) { //atribut
                                // if ($namaModel == $keys[17] || $namaModel == $keys[18] || $namaModel == $keys[20] || $namaModel == $keys[21]) { // 17 User 18 Anggota 20 Buku 21 Transaksi
                                $this->write('$newCount = ' . $namaModel . "::where('" . $words[$j + 1] . "','" . $words[$j + 2] . "')->count();\n\t");
                                $this->write('$this' . "->assertEquals(" . '$count, $newCount-1' . "); \n \t \n} \n \n");
                                // }
                            } else if (strtolower($words[$j]) == $keys[7]) { //berhasil
                                $this->write('$this' . "->assertEquals(" . '$count, $newCount-1' . "); \n \t \n} \n \n");
                            }
                        }
                        $array = [];
                        $used = [];
                    }
                }
            }
        }

        $this->write("}");
    }
}
