<?php
namespace Tests\Feature\Transaksi;
 
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
use App\Transaksi;
 
use App\Http\Controllers\TransaksiController;
 
class TransaksiTest extends TestCase { 
 
public function testUnit1(){
 	$count = Transaksi::where('tgl_pinjam','01012021')->count();
	$array1 = [
	'kode_transaksi'=>'TR00001',
	'tgl_pinjam'=>'2021-01-01',
	'tgl_kembali'=>'2021-01-05',
	'buku_id'=>'2',
	'nama'=>'Test',
	'anggota_id'=>'3',
	'keterangan'=>'Pinjam',
	];
	$controller = new TransaksiController();
	if($count==0){
		$controller->storeFunction($array1, $gambar=NULL);
	}
	$newCount = Transaksi::where('kode_transaksi','TR00001')->count();
	$this->assertEquals($count, $newCount-1); 
 	 
} 
 
}