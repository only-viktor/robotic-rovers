<?php
/**
 * Created by PhpStorm.
 * User: vic
 * Date: 10.02.19
 * Time: 17:37
 */

use app\models\Plateau;
use app\models\Rover;

class RoverTest extends \Codeception\Test\Unit
{

    public function testSetCoordinatesSuccess()
    {
        $plateau = new Plateau(5,5);
        $rover   = new Rover($plateau, [0,0,'N']);
    }

    /**
     * @expectedException  \yii\base\InvalidConfigException
     * @expectedExceptionMessageRegExp /Значение «X» не должно превышать 5/
     */
    public function testSetCoordinatesFailedX()
    {
        $plateau = new Plateau(5,5);
        $rover   = new Rover($plateau, [10,0,'N']);
    }

    /**
     * @expectedException  \yii\base\InvalidConfigException
     * @expectedExceptionMessageRegExp /Значение «Y» не должно превышать 5/
     */
    public function testSetCoordinatesFailedY()
    {
        $plateau = new Plateau(5,5);
        $rover   = new Rover($plateau, [0,10,'N']);
    }

    /**
     * @expectedException  \yii\base\InvalidConfigException
     * @expectedExceptionMessageRegExp /Значение «Z» неверно/
     */
    public function testSetCoordinatesFailedZ()
    {
        $plateau = new Plateau(5,5);
        $rover   = new Rover($plateau, [0,0,'XXX']);
    }

    public function testChangingDirection()
    {
        $plateau = new Plateau(5,5);
        $rover   = new Rover($plateau, [0,0,'N']);

        $rover->turnRight();
        $this->assertArraySubset(['z'=>'E'], $rover->coordinates);

        $rover->turnRight();
        $this->assertArraySubset(['z'=>'S'], $rover->coordinates);

        $rover->turnRight();
        $this->assertArraySubset(['z'=>'W'], $rover->coordinates);

        $rover->turnRight();
        $this->assertArraySubset(['z'=>'N'], $rover->coordinates);

        $rover->turnLeft();
        $this->assertArraySubset(['z'=>'W'], $rover->coordinates);

        $rover->turnLeft();
        $this->assertArraySubset(['z'=>'S'], $rover->coordinates);

        $rover->turnLeft();
        $this->assertArraySubset(['z'=>'E'], $rover->coordinates);

        $rover->turnLeft();
        $this->assertArraySubset(['z'=>'N'], $rover->coordinates);
    }

    public function testMoving()
    {
        $plateau = new Plateau(5,5);
        $rover   = new Rover($plateau, [0,0,'N']);

        $rover->move();
        $rover->move();
        $this->assertEquals(['x'=>0, 'y'=>2, 'z'=>'N'], $rover->coordinates);

        $rover->turnRight();
        $rover->move();
        $this->assertEquals(['x'=>1, 'y'=>2, 'z'=>'E'], $rover->coordinates);

        $rover->turnRight();
        $rover->move();
        $this->assertEquals(['x'=>1, 'y'=>1, 'z'=>'S'], $rover->coordinates);

        $rover->turnRight();
        $rover->move();
        $this->assertEquals(['x'=>0, 'y'=>1, 'z'=>'W'], $rover->coordinates);

        $rover->turnLeft();
        $rover->move();
        $this->assertEquals(['x'=>0, 'y'=>0, 'z'=>'S'], $rover->coordinates);
    }

    public function testExecuteInstructions()
    {
        $plateau = new Plateau(5,5);
        $rover   = new Rover($plateau, [0,0,'N']);

        $changes = $rover->execute('MMRMRMRMLM');
        $this->assertEquals([
            ['x' => 0, 'y' => 1, 'z' => 'N'],
            ['x' => 0, 'y' => 2, 'z' => 'N'],
            ['x' => 0, 'y' => 2, 'z' => 'E'],
            ['x' => 1, 'y' => 2, 'z' => 'E'],
            ['x' => 1, 'y' => 2, 'z' => 'S'],
            ['x' => 1, 'y' => 1, 'z' => 'S'],
            ['x' => 1, 'y' => 1, 'z' => 'W'],
            ['x' => 0, 'y' => 1, 'z' => 'W'],
            ['x' => 0, 'y' => 1, 'z' => 'S'],
            ['x' => 0, 'y' => 0, 'z' => 'S'],
        ], $changes);
    }
}
