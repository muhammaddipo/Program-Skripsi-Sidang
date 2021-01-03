<?php
namespace Tests\Feature\Auth;
 
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
use App\User;
 
use App\Http\Controllers\UserController;
 
class LoginTest extends TestCase { 
 
public function testUnit1(){
 	$response = $this->post('/login',[
	'email'=>'admin123@gilacoding.com',
	'password'=>'admin123',
	]);
	$response->assertRedirect('/home'); 
	} 
	 
	public function testUnit2(){
 	$response = $this->post('/login',[
	'email'=>'user123@gilacoding.com',
	'password'=>'user123',
	]);
	$response->assertRedirect('/home'); 
	} 
	 
	public function testUnit3(){
 	$response = $this->post('/login',[
	'email'=>'admin123@gilacoding.com',
	'password'=>'admin122',
	]);
	$response->assertRedirect(''); 
	} 
	 
	public function testUnit4(){
 	$response = $this->post('/login',[
	'email'=>'user123@gilacoding.com',
	'password'=>'user122',
	]);
	$response->assertRedirect(''); 
	} 
	 
	}