<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    /**
     * Test fitur login
     *
     * @return void
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email','admin123')
                ->type('password','admin123')
                ->press('Login')
                ->assertPathIs('/home');
        });
    }
    /**
     * test tambah transaksi
     */
    // public function testTransaksi()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->clickLink('Transaksi')
    //             ->clickLink("Tambah Transaksi")
    //             ->press("Cari Buku")
    //             ->waitForText("Cari Buku")
    //             ->click("tr[data-buku_judul='Pemrograman Android']")
    //             ->press("Cari Anggota")
    //             ->click("tr[data-anggota_nama='Muhammad Dipo']")
    //             ->type('keterangan','Pinjam')
    //             ->press("Submit")
    //             ->assertSee("Berhasil");
    //     });
    // }

    //**
    //  * Test fitur tambah user
    //  */
    // public function testTambahUser()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->clickLink('Master Data')
    //             ->clickLink('Data User')
    //             ->clickLink("Tambah User")
    //             ->type('name','Test')
    //             ->type('username','test')
    //             ->type('email','test@gmail.com')
    //             ->attach('gambar',base_path('public/images/user/kodekiddo.png'))
    //             ->select('level','user')
    //             ->type('password','password')
    //             ->type('password_confirmation','password')
    //             ->press('Register');

    //             $this->assertDatabaseHas('users', [
    //                 'username' => 'test'
    //             ]);
    //     });
    // }

    /**
     * Test fitur tambah anggota
     */
    // public function testTambahAnggota(){

    //     $this->browse(function (Browser $browser) {
    //         $browser->clickLink('Master Data')
    //             ->clickLink('Data Anggota')
    //             ->clickLink('Tambah Anggota')
    //             ->type('nama','Test')
    //             ->type('npm','000001')
    //             ->type('tempat_lahir','Bandung')
    //             ->keys('#tgl_lahir', '4271998')
    //             ->select('jenis_kelamin','Laki-Laki')
    //             ->select('prodi','TI')
    //             ->select('user_id','6')
    //             ->press('Submit');
    //     });
    //     $this->assertDatabaseHas('anggota', [
    //         'npm' => '000001'
    //     ]);
    // }
    

    // /**
    //  * Test fitur tambah buku (admin)
    //  */
    
    // public function testTambahBuku()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->clickLink('Master Data')
    //         ->clickLink('Data Buku')
    //         ->clickLink('Tambah Buku')
    //         ->type('judul','Pemrograman Python')
    //         ->type('isbn','123456789')
    //         ->type('pengarang','Muhammad Dipo')
    //         ->type('penerbit','PT Muhammad Dipo')
    //         ->type('tahun_terbit','2015')
    //         ->type('jumlah_buku','5')
    //         ->type('deskripsi','Buku untuk belajar Bahasa Pemrograman Python')
    //         ->select('lokasi','rak1')
    //         ->attach('cover',base_path('public/images/buku/python.png'))
    //         ->press('Submit');

    //         $this->assertDatabaseHas('buku', [
    //             'isbn' => '123456789'
    //         ]);
    //     });
    // }

    /**
     * Test fitur Log Out
     */
    public function testLogout()
    {
        $this->browse(function (Browser $browser) {
            $browser->clickLink('Hello')
            ->clickLink('Sign Out')
            ->assertPathIs('/login');
        });
    }

    
    
}
