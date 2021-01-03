<?php
namespace Tests\Feature\User;
 
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
use App\User;
 
use App\Http\Controllers\UserController;
 
class UserTest extends TestCase { 
 
public function testUnit1(){
 	$response = $this->post('/login',[
	'email'=>'admin123@gilacoding.com',
	'password'=>'admin123',
	]);
	$count = User::where('name','Test')->count();
	$array1 = [
	'name'=>'Test',
	'username'=>'test',
	'email'=>'test@gmail.com',
	'gambar'=>NULL,
	'level'=>'user',
	'password'=>'password1',
	];
	$controller = new UserController();
	if($count==0){
		$controller->storeFunction($array1, $gambar=NULL);
	}
	$newCount = User::where('name','Test')->count();
	$this->assertEquals($count, $newCount-1); 
 	 
} 
 
}