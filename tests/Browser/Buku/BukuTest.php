<?php
namespace Tests\Browser;
 
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
 
class BukuTest extends DuskTestCase { 
 
public function testUnit1(){
 	$this->browse(function (Browser $browser){
 	$browser->visit('/login') 
 	->type('email', 'admin123@gilacoding.com') 
 	->type('password', 'admin123') 
 	->press('Login')
 	->clickLink('Master Data')
 	->clickLink('Data Buku')
 	->clickLink('Tambah Buku')
 	->type('judul', 'Pemgrograman Python') 
 	->type('isbn', '123456789') 
 	->type('pengarang', 'Muhammad Dipo') 
 	->type('penerbit', 'PT Muhammad Dipo') 
 	->type('tahun_terbit', '2015') 
 	->type('jumlah_buku', '5') 
 	->type('deskripsi', 'Buku untuk belajar Bahasa Pemrograman Python') 
 	->select('lokasi','rak1')
 	->attach('cover',base_path('public/images/buku/python.png'))
 	->press('Submit')
 	;});
 	$this->assertDatabaseHas('buku',[ 
 	'isbn' => '123456789'
	]);
 	 
} 
 
}