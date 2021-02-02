<?php
namespace Tests\Feature\Buku;
 
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
use App\Buku;
 
use App\Http\Controllers\BukuController;
 
class BukuTest extends TestCase { 
 
public function testUnit1(){
 	$count = Buku::where('isbn','123456789')->count();
	$array1 = [
	'judul'=>'Pemgrograman Python',
	'isbn'=>'123456789',
	'pengarang'=>'Muhammad Dipo',
	'penerbit'=>'PT Muhammad Dipo',
	'tahun_terbit'=>'2015',
	'jumlah_buku'=>'5',
	'deskripsi'=>'Buku untuk belajar Bahasa Pemrograman Python',
	'lokasi'=>'rak1',
	'cover'=>'python',
	];
	$controller = new BukuController();
	if($count==0){
		$controller->storeFunction($array1, $gambar=NULL);
	}
	$newCount = Buku::where('isbn','123456789')->count();
	$this->assertEquals($count, $newCount-1); 
 	 
} 
 
}