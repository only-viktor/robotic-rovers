<?php
/**
 * Created by PhpStorm.
 * User: vic
 * Date: 10.02.19
 * Time: 16:43
 */

namespace app\models;

use yii\base\DynamicModel;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property array  $coordinates
 * @property int    $x
 * @property int    $y
 * @property string $z
 */
class RoverPosition extends Model
{
    const DIRECTION_NORTH = 'N';
    const DIRECTION_EAST  = 'E';
    const DIRECTION_SOUTH = 'S';
    const DIRECTION_WEST  = 'W';

    const DIRECTIONS = [
        self::DIRECTION_NORTH,
        self::DIRECTION_EAST,
        self::DIRECTION_SOUTH,
        self::DIRECTION_WEST,
    ];

    private
        $rover,
        $plateau,
        $x = 0,
        $y = 0,
        $z = self::DIRECTION_NORTH;

    public function __construct(Plateau $plateau, Rover $rover, array $config = [])
    {
        $this->plateau = $plateau;
        $this->rover   = $rover;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['x','y','z'], 'required'],
            [['x'], 'integer', 'min' => 0, 'max' => $this->getMaxX()],
            [['y'], 'integer', 'min' => 0, 'max' => $this->getMaxY()],
            [['z'], 'in', 'range' => self::DIRECTIONS],
        ];
    }

    private function getMaxX()
    {
        return $this->plateau->width;
    }

    private function getMaxY()
    {
        return $this->plateau->height;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getZ()
    {
        return $this->z;
    }

    public function getCoordinates()
    {
        return [
            'x'=>$this->x,
            'y'=>$this->y,
            'z'=>$this->z
        ];
    }

    public function setCoordinates(array $coordinates)
    {
        if (ArrayHelper::isIndexed($coordinates)) {
            $coordinates = array_combine(['x','y','z'], $coordinates);
        }

        $data = array_merge($this->getAttributes(['x','y','z']), $coordinates);

        $model = DynamicModel::validateData($data, $this->validators);

        if ($model->hasErrors()){
            $error = "Error changing rover's position: " . implode(PHP_EOL, $model->firstErrors);
            throw new InvalidConfigException($error);
        }

        foreach($coordinates as $name=>$value) {
            $this->$name = $value;
        }
    }

}
