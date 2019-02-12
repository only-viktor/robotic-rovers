<?php
/**
 * Created by PhpStorm.
 * User: vic
 * Date: 10.02.19
 * Time: 16:25
 */

namespace app\commands;

use app\models\Plateau;
use app\models\Rover;
use yii\console\Controller;
use yii\helpers\Console;

class ExampleController extends Controller
{
    public function actionRun()
    {
        $zoom = Console::input('Enter plateau zoom (example: 5 5): ');
        if (!$zoom) {
            Console::error('Error: Plateau zoom is not entered.');
            exit(1);
        }

        list($width,$height) = preg_split('/\s+/', $zoom, -1, PREG_SPLIT_NO_EMPTY);

        $plateau = new Plateau($width, $height);

        while (true) {
            $coordinates = Console::input('Enter rover coordinates (example: 1 2 N): ');
            if (!$coordinates) {
                Console::error('Error: Rover coordinates are not entered.');
                exit(1);
            }

            list($x, $y, $z) = preg_split('/\s+/', $coordinates, -1, PREG_SPLIT_NO_EMPTY);

            $rover = new Rover($plateau, [$x, $y, $z]);

            $instructions = Console::input('Enter rover instructions (example: LMLMLMLMM): ');
            if (!$instructions) {
                Console::error('Error: Instructions are not entered.');
                exit(1);
            }

            $changes = $rover->execute($instructions);

            foreach ($changes as $coordinates) {
                list($x, $y, $z) = array_values($coordinates);
                $output = $x . ' ' . $y . ' ' . $z;
                Console::output($output);
            }
        }
    }
}
