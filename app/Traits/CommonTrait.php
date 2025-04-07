<?php

namespace App\Traits;

trait CommonTrait
{
    public function toggleStatus($id, $modelName)
    {
        $model = "App\\Models\\$modelName";
        $record = $model::where('id', $id)->first();

        if ($record) {
            $record->status = !$record->status;
            $record->save();
        }
    }


    public function delete($id, $modelName, $redirect = null)
    {
        $model = "App\\Models\\$modelName";
        $record = $model::where('id', $id)->first();

        if ($record) {
            try {
                $record->delete();
            } catch (\Exception $e) {
                return;
            }
        }

        if ($redirect) {
            return redirect()->route($redirect);
        }
    }
}
