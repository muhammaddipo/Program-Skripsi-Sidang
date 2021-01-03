<?php
namespace Tests\Feature\Transaksi;
 
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
use App\Transaksi;
 
use App\Http\Controllers\TransaksiController;
 
use  App\Anggota;
 
class TransaksiTest extends TestCase { 
 
public function testUnit1(){
 	$response = $this->post('/login',[
	'email'=>'admin123@gilacoding.com',
	'password'=>'admin123',
	]);
	$count = Transaksi::where('anggota_id',3)->count();
	$array1 = [
	'kode_transaksi'=>'TR00001',
	'tgl_pinjam'=>'2021-01-01',
	'tgl_kembali'=>'2021-01-05',
	'buku_id'=>'2',
	'nama'=>'Test',
	'anggota_id'=>'3',
	'ket'=>'Pinjam',
	];
	$controller = new TransaksiController();
	if($count<=3){
		$controller->storeFunction($array1, $gambar=NULL);
	}
	$newCount = Transaksi::where('anggota_id',3)->count();
	$this->assertEquals($count, $newCount-1); 
 	 
} 
 
}