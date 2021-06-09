<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as Storage;

class TaskController extends Controller
{
    public function convertToShellScript(Request $request) {
        $data = $request->json()->all();
        $fileName = $data['filename'];
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
        $orderedCommands = [];
        $dependentCommands = [];
        $i = 0;
        foreach($tasks as &$currentTask) {
            $i++;
            if(!isset($currentTask['dependencies']) || empty($currentTask['dependencies'])) {
                $currentTask['weight'] = 0;
                array_push($orderedCommands, $currentTask['command']);
            }
            else {
                $currentTask['weight'] = $i + count($currentTask['dependencies']);
                $dependentCommands[$currentTask['weight']] = $currentTask['command'];
            }
        }
        return array_merge($orderedCommands,array_values($dependentCommands));
    }
}
