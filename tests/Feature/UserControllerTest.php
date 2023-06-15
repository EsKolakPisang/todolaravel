<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testLoginPage()
    {
        $this->get('/login')
            ->assertSeeText('Login');
    }

    public function testLoginPageForMember()
    {
        $this->withSession([
            'user' => 'ninuk'
        ])->get('/login')
            ->assertRedirect('/');
    }

    public function testLoginSuccess()
    {
        $this->post('/login', [
            'user' => 'ninuk',
            'password' => 'rahasia'
        ])->assertRedirect('/')
            ->assertSessionHas('user', 'ninuk');
    }

    public function testLoginForUserAlreadyLogin()
    {
        $this->withSession([
            'user' => 'user'
        ])->post('/login', [
            'user' => 'ninuk',
            'password' => 'rahasia'
        ])->assertRedirect('/');
    }

    public function testLoginValidationError()
    {
        $this->post('/login', [])
            ->assertSeeText('User or Password is required');
    }

    public function testLoginFailed()
    {
        $this->post('/login', [
            'user' => 'wrong',
            'password' => 'wrong'
        ])->assertSeeText('User or Password is Wrong');
    }

    public function testLogout()
    {
        $this->withSession([
            'user' => 'ninuk'
        ])->post('/logout')
            ->assertRedirect('/')
            ->assertSessionMissing('user');
    }
    public function testLogoutGuest()
    {
        $this->post('/logout')
            ->assertRedirect('/');
    }
}
