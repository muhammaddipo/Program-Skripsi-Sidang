<?php
namespace Tests\Browser;
 
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
 
class UserTest extends DuskTestCase { 
 
public function testUnit1(){
 	$this->browse(function (Browser $browser){
 	$browser->visit('/login') 
 	->type('email', 'admin123@gilacoding.com') 
 	->type('password', 'admin123') 
 	->press('Login')
 	->clickLink('Master Data')
 	->clickLink('Data User')
 	->clickLink('Tambah User')
 	->type('name', 'Test') 
 	->type('username', 'test') 
 	->type('email', 'test@gmail.com') 
 	->attach('gambar',base_path('public/images/user/kodekiddo.png'))
 	->select('level','user')
 	->type('password', 'password1') 
 	->type('password_confirmation', 'password1') 
 	->press('Register')
 	;});
 	$this->assertDatabaseHas('users',[ 
 	'username' => 'test'
	]);
 	 
} 
 
}