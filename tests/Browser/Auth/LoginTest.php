<?php
namespace Tests\Browser;
 
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
 
class LoginTest extends DuskTestCase { 
 
public function testUnit1(){
 	$this->browse(function (Browser $browser){
 	$browser->visit('/login') 
 	->type('email', 'admin123@gilacoding.com') 
 	->type('password', 'admin123') 
 	->press('Login')
 	->assertPathIs('/home'); 
 	}); 
} 
 
public function testUnit2(){
 	$this->browse(function (Browser $browser){
 	$browser->visit('/login') 
 	->type('email', 'user123@gilacoding.com') 
 	->type('password', 'user123') 
 	->press('Login')
 	->assertPathIs('/home'); 
 	}); 
} 
 
public function testUnit3(){
 	$this->browse(function (Browser $browser){
 	$browser->visit('/login') 
 	->type('email', 'admin123@gilacoding.com') 
 	->type('password', 'admin122') 
 	->press('Login')
 	->assertPathIs('/login'); 
 	}); 
} 
 
public function testUnit4(){
 	$this->browse(function (Browser $browser){
 	$browser->visit('/login') 
 	->type('email', 'user123@gilacoding.com') 
 	->type('password', 'user122') 
 	->press('Login')
 	->assertPathIs('/login'); 
 	}); 
} 
 
}