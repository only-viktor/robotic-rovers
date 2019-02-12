<?php
/**
 * Created by PhpStorm.
 * User: vic
 * Date: 10.02.19
 * Time: 16:29
 */

namespace app\models;

use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * @property array $coordinates
 */
class Rover extends Model
{
    const COMMAND_TURN_LEFT  = 'L';
    const COMMAND_TURN_RIGHT = 'R';
    const COMMAND_MOVE       = 'M';

    private
        /**
         * @var Plateau
         */
        $plateau,
        /**
         * @var RoverPosition
         */
        $position;

    public function __construct(Plateau $plateau, array $coordinates, array $config = [])
    {
        $this->plateau  = $plateau;
        $this->position = new RoverPosition($plateau, $this);
        $this->position->setCoordinates($coordinates);

        parent::__construct($config);
    }

    public function getCoordinates()
    {
        return $this->position->coordinates;
    }

    public function turnLeft()
    {
        $position = $this->position;

        $index = array_search($position->z, RoverPosition::DIRECTIONS);

        if ($index===0){
            $z = RoverPosition::DIRECTIONS[3];
        } else {
            $z = RoverPosition::DIRECTIONS[$index - 1];
        }

        $position->setCoordinates(['z' => $z]);
    }

    public function turnRight()
    {
        $position = $this->position;

        $index = array_search($position->z, RoverPosition::DIRECTIONS);

        if ($index===3){
            $z = RoverPosition::DIRECTIONS[0];
        } else {
            $z = RoverPosition::DIRECTIONS[$index + 1];
        }

        $position->setCoordinates(['z' => $z]);
    }

    public function move()
    {
        $position = $this->position;

        switch($position->z) {
            case RoverPosition::DIRECTION_NORTH:
                $change = ['y' => $position->y + 1];
                break;

            case RoverPosition::DIRECTION_EAST:
                $change = ['x' => $position->x + 1];
                break;

            case RoverPosition::DIRECTION_SOUTH:
                $change = ['y' => $position->y - 1];
                break;

            case RoverPosition::DIRECTION_WEST:
                $change = ['x' => $position->x - 1];
                break;
        }

        $position->setCoordinates($change);
    }

    /**
     * @param string $instructions
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function execute($instructions)
    {
        $commands = str_split($instructions);

        $position = $this->position;
        $changes  = [];

        foreach($commands as $command) {

            switch($command) {

                case self::COMMAND_MOVE:
                    $this->move();
                    break;

                case self::COMMAND_TURN_LEFT:
                    $this->turnLeft();
                    break;

                case self::COMMAND_TURN_RIGHT:
                    $this->turnRight();
                    break;

                default:
                    throw new InvalidConfigException('Invalid inststruction command: '.$command);
            }

            $changes[] = $position->coordinates;
        }

        return $changes;
    }
}
