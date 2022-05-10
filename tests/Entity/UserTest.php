<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Entity\User;

final class UserTest extends TestCase {
    public function testNewUser() {
        $user = $this->getUser();
        $this->assertTrue($user->getUserIdentifier() === 'test');
        $this->assertTrue($user->getPassword() === 'test');
        $this->assertTrue($user->getRoles() === ['ROLE_USER']);
    }

    private function getUser(): User {
        $user = new User();
        $user->setUsername('test');
        $user->setPassword('test');
        $user->setRoles(['ROLE_USER']);
        return $user;
    }
}