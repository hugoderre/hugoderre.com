<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\TestsProvider\Entity\UserProvider;

final class UserTest extends TestCase {
    public function testUsernameCanBeChanged() {
        $user = UserProvider::getRegularUser(); // John
        $user->setUsername('John Doe'); // John Doe
        $this->assertTrue($user->getUserIdentifier() === 'John Doe');
    }
}