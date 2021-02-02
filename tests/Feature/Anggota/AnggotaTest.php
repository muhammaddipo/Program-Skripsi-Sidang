<?php
namespace Tests\Feature\Anggota;
 
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
use App\Anggota;
 
use App\Http\Controllers\AnggotaController;
 
class AnggotaTest extends TestCase { 
 
public function testUnit1(){
 	$count = Anggota::where('npm','0000001')->count();
	$array1 = [
	'nama'=>'Test',
	'npm'=>'0000001',
	'tempat_lahir'=>'Bandung',
	'tgl_lahir'=>'1998-04-27',
	'jenis_kelamin'=>'Laki-Laki',
	'prodi'=>'TI',
	'user_id'=>'3',
	];
	$controller = new AnggotaController();
	if($count==0){
		$controller->storeFunction($array1, $gambar=NULL);
	}
	$newCount = Anggota::where('npm','0000001')->count();
	$this->assertEquals($count, $newCount-1); 
 	 
} 
 
}