<?php
namespace Tests\Browser;
 
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
 
class AnggotaTest extends DuskTestCase { 
 
public function testUnit1(){
 	$this->browse(function (Browser $browser){
 	$browser->visit('/login') 
 	->type('email', 'admin123@gilacoding.com') 
 	->type('password', 'admin123') 
 	->press('Login')
 	->clickLink('Master Data')
 	->clickLink('Data Anggota')
 	->clickLink('Tambah Anggota')
 	->type('nama', 'Test') 
 	->type('npm', '10000333') 
 	->type('tempat_lahir', 'Bandung') 
 	->keys('#tgl_lahir','27041998')
 	->select('jenis_kelamin','Laki-Laki')
 	->select('prodi','TI')
 	->select('user_id','3')
 	->press('Submit')
 	;});
 	$this->assertDatabaseHas('anggota',[ 
 	'npm' => '10000333'
	]);
 	 
} 
 
}