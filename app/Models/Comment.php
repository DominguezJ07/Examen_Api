<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
     'texto', 'valor_estrella', 'user_id', 'publication_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
       
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
       'valor_estrella' => 'integer'
    ];

     // LISTAS BLANCAS PARA API
    protected $allowIncluded = ['user', 'publication'];
    protected $allowFilter = ['id', 'texto', 'valor_estrella'];
    protected $allowSort = ['id', 'texto', 'valor_estrella'];


    public function user () {
        return $this->belongsTo(User::class);
    }
    public function publication (){
        return $this->belongsTo(Publication::class);
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
