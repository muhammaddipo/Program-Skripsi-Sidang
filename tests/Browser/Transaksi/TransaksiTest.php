<?php
namespace Tests\Browser;
 
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
 
class TransaksiTest extends DuskTestCase { 
 
public function testUnit1(){
 	$this->browse(function (Browser $browser){
 	$browser->visit('/login') 
 	->type('email', 'admin123@gilacoding.com') 
 	->type('password', 'admin123') 
 	->press('Login')
 	->clickLink('Transaksi Buku')
 	->clickLink('Tambah Transaksi')
 	->press('Cari Buku')
 	->waitForText('Cari Buku')
 	->click("tr[data-buku_judul='Pemrograman Android']")
	->press('Cari Anggota')
 	->click("tr[data-anggota_nama='Test']")
	->type('keterangan', 'Pinjam') 
 	->press('Submit')
 	->assertSee("Berhasil"); 
 	}); 
} 
 
}