<?php

namespace WhitePage\Models;

use WhitePage\Traits\Makeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Repository
{
    use Makeable;

    public function __construct(
        private Model $model
    ) {
    }

    public function commit(Collection $postData)
    {
        try {
            DB::beginTransaction();

            $modelData = $postData->filter(fn ($field) => empty($field['relationship']));

            $this->model->fill($modelData->toArray());

            foreach ($postData->except($modelData->keys()) as $field) {

                $relationship = $field['relationship'];

                if (!$relationship instanceof Relation) {
                    continue;
                }

                switch (true) {
                    case $relationship instanceof BelongsTo:
                        $this->model->{$relationship->getRelationName()}()->associate($field['value']);
                        break;

                    case $relationship instanceof MorphToMany:
                        $this->model->{$relationship->getRelationName()}()->sync($field['value']);
                        break;
                }
            }

            $this->model->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
