<?php
/**
 * Created by PhpStorm.
 * User: vic
 * Date: 10.02.19
 * Time: 16:26
 */

namespace app\models;

use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * @property int $width
 * @property int $height
 */
class Plateau extends Model
{
    private
        /**
         * @var int
         */
        $width,
        /**
         * @var int
         */
        $height;

    public function __construct($width, $height, array $config = [])
    {
        $this->width  = $width;
        $this->height = $height;

        parent::__construct($config);

        if (!$this->validate()) {
            $error = "Error in plateau zoom: " . implode(PHP_EOL, $this->firstErrors);
            throw new InvalidConfigException($error);
        }
    }

    public function rules()
    {
        return [
            [['width','height'], 'integer', 'min'=>1]
        ];
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

}
