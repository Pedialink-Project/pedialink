<?php

namespace Tests\Unit;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Helpers\IntToDayName;
use PHPUnit\Framework\TestCase;

class IntToDayNameTest extends TestCase
{
    /**
     * @dataProvider dayNameProvider
     */
    public function testConvertReturnsExpectedDayName(int $day, string $expected): void
    {
        $this->assertSame($expected, IntToDayName::convert($day));
    }

    public function dayNameProvider(): array
    {
        return [
            [0, 'Monday'],
            [1, 'Tuesday'],
            [2, 'Wednesday'],
            [3, 'Thursday'],
            [4, 'Friday'],
            [5, 'Saturday'],
            [6, 'Sunday'],
        ];
    }

    public function testConvertReturnsUnknownForInvalidDay(): void
    {
        $this->assertSame('Unknown', IntToDayName::convert(7));
        $this->assertSame('Unknown', IntToDayName::convert(-1));
    }
}
