<?php

namespace WhitePage\Builders;

use WhitePage\Facades\WhitePage;
use WhitePage\Components\AbstractMethod;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use WhitePage\Contracts\SectionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class FormBuilder
{
    private Collection $fields;
    private Collection $relationships;
    private Validator $validator;

    public function __construct(
        private Request $request,
        private SectionInterface $section,
        private Model|null $model,
    ) {
    }

    public function fields(array $array)
    {
        $this->fields = collect($array)->mapWithKeys(function ($field) {

            if (method_exists($field, 'withRelationship') && $field->withRelationship()) {

                $relationshipName = key($field->withRelationship());
                $label = current($field->withRelationship());

                $relationship = $this->section->getModelClass()->{$relationshipName}();

                $field->setRelationship($relationship);
                $field->options($relationship->getRelated()->pluck($label, 'id'));
            }

            return [$field->getName() => $field];
        });

        return $this;
    }

    public function relationships(array $array)
    {
        $this->relationships = collect($array)->mapWithKeys(function ($relation) {

            $relationship = $this->model?->{$relation->getName()}();

            if ($relationship instanceof HasMany) {
                $relation->setRelationship($relationship);
            }

            return [$relation->getName() => $relation];
        });

        return $this;
    }

    public function build()
    {
        if (empty($this->relationships)) {
            $this->relationships = collect();
        }

        if ($this->model instanceof Model) {
            $this->fields->map(
                function ($field) {

                    if ($field->relationship instanceof Relation) {

                        $relationModel = $this->model->{$field->relationship->getRelationName()};

                        if ($field->relationship instanceof MorphToMany) {
                            $value = $relationModel->first()->id;
                        }

                        if ($field->relationship instanceof BelongsTo) {
                            $value = $relationModel?->id;
                        }
                    } else {
                        $value = $this->model->{$field->getName()};
                    }

                    $field
                        ->setValue($value)
                        ->setModel($this->model);
                }
            );
        }

        if ($this->request->isMethod('post')) {

            $rules = $this->fields
                ->mapWithKeys(fn ($field) => [$field->getName() => $field->rule()])
                ->toArray();

            $this->validator = ValidatorFacade::make($this->request->all(), $rules);

            $this->fields->map(
                fn ($field) => $field->setValue($this->request->input($field->getName()))
            );

            foreach ($this->validator->errors()->getMessages() as $fieldName => $fieldErrors) {
                $this->fields[$fieldName]->setError(implode(', ', $fieldErrors));
            };
        }

        return $this;
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function getRelationships(): Collection
    {
        return $this->relationships;
    }

    public function getAction(int $id = null): string
    {
        return href(
            WhitePage::CMS_ROOT_PREFIX,
            $this->section->getName(),
            $id
                ? AbstractMethod::EDIT_METHOD
                : AbstractMethod::CREATE_METHOD,
            $id);
    }

    public function getId(): string
    {
        $modelId = $this->getModelId();
        $formId = sprintf('%s', Str::ucfirst($this->section->getName()));
        $formId = $modelId ? $formId . $modelId : $formId;

        return $formId;
    }

    public function getModelId(): int|null
    {
        return $this->model?->id;
    }

    public function passed(): Collection
    {
        $this->validate();
        return $this->getValidatedData();
    }

    private function getValidatedData(): Collection
    {
        return $this->fields->mapWithKeys(function ($field) {

            $value = $field->getValue();

            // password exception
            if ($field->getName() === 'password' && empty($value)) {
                return [];
            }

            if ($field->relationship instanceof Relation) {
                $value = [
                    'relationship' => $field->relationship,
                    'value' => $field->getValue()
                ];
            }

            return [$field->getName() => $value];
        });
    }

    private function validate(): void
    {
        if ($this->validator->fails()) {
            throw ValidationException::withMessages($this->validator->errors()->toArray());
        }
    }
}
