<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class GeneratePHPUnitController extends Controller
{
    private $newDir, $fileWriter;

    public function __construct()
    {
        $this->newDir = '..\\..\\..\\tests\\Feature\\';
    }

    public function setFileWriter($fileWriter)
    {
        $this->fileWriter = $fileWriter;
    }

    private function write($text)
    {
        fwrite($this->fileWriter, $text);
    }

    public function getNewDir()
    {
        return $this->newDir;
    }

    public function writeBody($newFileName, $fileReader, $namaModel, $namaFolder)
    {
        // atribut bantuan
        $keys = [
            "Scenario:", "Given", "When", "And", "Then", "halaman", "Login", //6
            "berhasil", "tulisan", "Sign", "kembali", "email", "password", //12
            "Submit", "atribut", "Register", "gambar", "User", "Anggota", //18
            "tgl", "Buku", "Transaksi", "nama", "Berhasil", "tetap" // 24
        ];

        $this->write("<?php\n");
        $this->write("namespace Tests\\Feature\\" . $namaFolder . ";\n \n");

        $this->write("use Tests\TestCase;\n");
        $this->write("use Illuminate\Foundation\Testing\WithFaker;\n");
        $this->write("use Illuminate\Foundation\Testing\RefreshDatabase;\n \n");

        $pathModel = "App\\" . $namaModel;
        $model = new $pathModel;
        $fillable = $model->getFillable();

        $pathController =  "App\\Http\\Controllers\\" . $namaModel . "Controller";
        $controller = new $pathController;

        $this->write("use " . $pathModel . ";\n \n");
        $this->write("use " . $pathController . ";\n \n");
        if ($namaModel == $keys[21]) { //transaksi
            $this->write("use  App\\Anggota;\n \n");
        }

        $this->write("class " . $newFileName . "Test" . " extends TestCase { \n \n");


        $banyakTest = 1;

        $used = []; // digunakan untuk atr di dalam model
        $array = []; // digunakan untuk menyimpan key dan value dari model
        $logout = false;

        if ($fileReader) {
            while (($line = fgets($fileReader)) !== false) {
                $words = preg_split('/\s+/', $line, -1, PREG_SPLIT_NO_EMPTY);

                // idx untuk words
                for ($i = 0; $i < sizeof($words); $i++) {

                    if ($words[$i] == $keys[0]) { // Scenario:
                        $this->write("public function testUnit" . $banyakTest . "(){\n \t");
                        $banyakTest++;
                    } else if ($words[$i] == $keys[1]) { // Given
                        for ($j = 0; $j < sizeof($words); $j++) {
                            if ($words[$j] == $keys[5]) { //halaman
                                $this->write('$response = $this->post(' . "'/" . $words[$j + 1] . "',[\n\t");
                            }
                        }
                    } else if ($words[$i] == $keys[2] || $words[$i] == $keys[3]) { // When & And
                        for ($j = 0; $j < sizeof($words); $j++) {
                            foreach ($fillable as $atr) {
                                if ($words[$j] == $atr) {
                                    if (in_array($words[$j], $used) == false) {
                                        $key = $words[$j];
                                        $array[$key] = [];
                                        $str = "";
                                        for ($k = $j + 2; $k < sizeof($words); $k++) {
                                            $str .= $words[$k];
                                            if ($k != sizeof($words) - 1) {
                                                $str .= " ";
                                            }
                                        }

                                        array_push($used, $words[$j]);
                                        array_push($array[$key], $str);
                                    }
                                }
                            }
                            if (in_array($words[$j], $used) == false) { //untuk email dan password jika tidak ada di model
                                if ($words[$j] == $keys[11] || $words[$j] == $keys[12] || $words[$j] == $keys[22]) { //11 email //12 password 22 nama
                                    $key = $words[$j];
                                    $array[$key] = [];

                                    array_push($used, $words[$j]);
                                    array_push($array[$key], $words[$j + 2]);
                                }
                            }

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
                            } else if ($words[$j] == $keys[13] || $words[$j] == $keys[15]) { // 13 submit 15 Register
                                if ($namaModel == $keys[17]) { //user
                                    $atrNama = "name";
                                } else if ($namaModel == $keys[18]) {
                                    $atrNama = "nama";
                                }

                                if ($namaModel == $keys[17] || $namaModel == $keys[18]) { // 17 User 18 Anggota
                                    $this->write('$count = ' . $namaModel . "::where('" . $atrNama . "','" . $array[$atrNama][0] . "')->count();\n\t");
                                } else if ($namaModel == $keys[20]) { // buku
                                    $this->write('$count = ' . $namaModel . "::where('isbn','" . $array['isbn'][0] . "')->count();\n\t");
                                } else if ($namaModel == $keys[21]) { // transaksi
                                    $this->write('$count = ' . $namaModel . "::where('anggota_id'," . $array['anggota_id'][0] . ")->count();\n\t");
                                }

                                $this->write('$array1 = [' . "\n\t");

                                foreach ($array as $key => $value) {
                                    if ($key == $keys[16]) { //gambar
                                        $this->write("'" . $key . "'=>NULL,\n\t");
                                    } else if (str_contains($key, $keys[19])) { //kalau ada tgl
                                        $tgl = substr($value[0], 4, 4) . "-" . substr($value[0], 0, 2) . "-" . substr($value[0], 2, 2); // mm/dd/yyyy -> yyyy-mm-dd
                                        $this->write("'" . $key . "'=>'" . $tgl . "',\n\t");
                                    } else {
                                        $this->write("'" . $key . "'=>'" . $value[0] . "',\n\t");
                                    }
                                }
                                $this->write('];' . "\n\t");
                                $this->write('$controller = new ' . $namaModel . "Controller();\n\t");
                                if ($namaModel == $keys[21]) { //transaksi
                                    $this->write('if($count<=3){' . "\n\t\t");
                                } else {
                                    $this->write('if($count==0){' . "\n\t\t");
                                }

                                $this->write('$controller->storeFunction' . '($array1, $gambar=NULL)' . ";\n\t");
                                $this->write("}\n\t");

                                if ($namaModel == $keys[17] || $namaModel == $keys[18]) { // 17 User 18 Anggota
                                    $this->write('$newCount = ' . $namaModel . "::where('" . $atrNama . "','" . $array[$atrNama][0] . "')->count();\n\t");
                                } else if ($namaModel == $keys[20]) { // buku
                                    $this->write('$newCount = ' . $namaModel . "::where('isbn','" . $array['isbn'][0] . "')->count();\n\t");
                                } else if ($namaModel == $keys[21]) { // transaksi
                                    $this->write('$newCount = ' . $namaModel . "::where('anggota_id'," . $array['anggota_id'][0] . ")->count();\n\t");
                                }
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
                            } else if ($words[$j] == $keys[10] || $words[$j] == $keys[24]) { // 10 kembali 24 tetap
                                $this->write('$response->assertRedirect(' . "''" . "); \n\t} \n\t \n\t");
                            } else if ($words[$j] == $keys[14]) { //atribut
                                if ($namaModel == $keys[17] || $namaModel == $keys[18] || $namaModel == $keys[20] || $namaModel == $keys[21]) { // 17 User 18 Anggota 20 Buku 21 Transaksi
                                    $this->write('$this' . "->assertEquals(" . '$count, $newCount-1' . "); \n \t \n} \n \n");
                                }
                            } else if ($words[$j] == $keys[23]) { //berhasil
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
