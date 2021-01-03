<?php
namespace Tests\Feature\Auth;
 
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
use App\User;
 
use App\Http\Controllers\UserController;
 
class LogoutTest extends TestCase { 
 
public function testUnit1(){
 	$response = $this->post('/login',[
	'email'=>'admin123@gilacoding.com',
	'password'=>'admin123',
	]);
	$response = $this->post('/logout');
	$response->assertRedirect(''); 
	} 
	 
	}