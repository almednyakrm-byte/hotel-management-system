<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Auth\Auth;
use App\Database\Database;

class TestAuth extends TestCase
{
    private $auth;
    private $database;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $this->auth = new Auth($this->database);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->database
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username, 'password' => $password]);

        $result = $this->auth->login($username, $password);

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->database
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username, 'password' => 'testpassword']);

        $result = $this->auth->login($username, $password);

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $email = 'test@example.com';

        $this->database
            ->method('getUser')
            ->with($username)
            ->willReturn(null);

        $this->database
            ->method('insertUser')
            ->with($username, $password, $email)
            ->willReturn(true);

        $result = $this->auth->register($username, $password, $email);

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';
        $email = 'test@example.com';

        $this->database
            ->method('getUser')
            ->with($username)
            ->willReturn(['username' => $username]);

        $result = $this->auth->register($username, $password, $email);

        $this->assertFalse($result);
    }

    public function testSessionLogin()
    {
        $username = 'testuser';

        $_SESSION['username'] = $username;

        $result = $this->auth->isLogged();

        $this->assertTrue($result);
    }

    public function testSessionLogout()
    {
        session_unset();
        session_destroy();

        $result = $this->auth->isLogged();

        $this->assertFalse($result);
    }
}