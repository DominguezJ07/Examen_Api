<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Role extends Model
{
    use HasFactory;

     protected $fillable = [
     'nombre', 'permisos'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    //no tengo campos sensibles

    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'permisos' => 'array'
    ];

      // LISTAS BLANCAS PARA API
    protected $allowIncluded = ['users'];
    protected $allowFilter = ['id', 'nombre', 'permisos'];
    protected $allowSort = ['id', 'nombre', 'created_at', 'updated_at'];

    public function users (){
        return $this->hasMany(User::class);
    }
    

    // SCOPES PARA API
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }
        
        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);
        
        foreach ($relations as $key => $relationship) {
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }
        
        $query->with($relations);
    }
    
    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }
        
        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);
        
        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $query->where($filter, 'LIKE', '%' . $value . '%');
            }
        }
    }
    
    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }
        
        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);
        
        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            if (substr($sortField, 0, 1) == '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }
            
            if ($allowSort->contains($sortField)) {
                $query->orderBy($sortField, $direction);
            }
        }
    }
    
    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));
            if ($perPage) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}