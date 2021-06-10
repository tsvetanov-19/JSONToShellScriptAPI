<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as Storage;

class TaskController extends Controller
{
    public function convertToShellScript(Request $request) {
        $data = $request->json()->all();
        $fileName = $data['filename'];
        $fileContents = '';
        if(isset($data['tasks'])) {
            $orderedCommands = $this->sortCommands($data['tasks']);
            try {
                $fileContents = implode(PHP_EOL,$orderedCommands);
                Storage::put($fileName, $fileContents);
            } catch(\Exception $e) {
                dd($e);
            }
        }
        return $fileContents;
    }

    private function sortCommands($tasks): array {
        $i = 0;
        $nonDependent = 0;
        $ordered = [];
        $t = [];
        foreach($tasks as $currentTask){
            if(!isset($currentTask['dependencies'])) {
                $ordered[] = $currentTask['command'];
                array_unshift($t, $currentTask['name']);
                $nonDependent++;
            }
            else {
                $t[$i] = $currentTask['name'];
            }
            $i++;
        }

        $n = count($t);
        for($j = $nonDependent; $j <$n; $j++) {
            foreach($tasks as $currentTask) {
                if(isset($currentTask['dependencies'])) {
                    while(!empty($currentTask['dependencies'])) {
                        $dep = array_shift($currentTask['dependencies']);
                        $k = array_search($dep, $t);
                        if($k > $j) {
                            $t[$k] = $t[$j];
                            $t[$j] = $dep;
                        }
                    }
                }
            }
        }

        for($l = $nonDependent; $l <$n; $l++) {
            foreach ($tasks as $task) {
                if($task['name'] == $t[$l]) {
                    $ordered[] = $task['command'];
                }
            }
        }

        return $ordered;
    }
}
