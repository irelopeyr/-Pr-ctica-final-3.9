<?php
//Sirve para definir el modelo de Project y sus relaciones con el modelo User
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['user_id', 'title', 'description', 'due_date', 'status'])]
class Project extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }
    // Define la relación entre Project y User, indicando que un proyecto pertenece a un usuario (el propietario)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define la relación entre Project y User, indicando que un proyecto puede tener muchos miembros (usuarios) a través de la tabla intermedia project_user
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'contribution_hours');
    }
}