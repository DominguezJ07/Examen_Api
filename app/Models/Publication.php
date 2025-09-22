<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
     'titulo', 'precio', 'descripcion', 'visibilidad', 'seller_id', 'category_id'];

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
      'precio' => 'decimal:2',      // Para manejar decimales
        'visibilidad' => 'boolean',
        
    ];
     // LISTAS BLANCAS PARA API
    protected $allowIncluded = ['category', 'seller', 'complaints', 'images', 'users'];
    protected $allowFilter = ['id',  'titulo', 'precio', 'descripcion', 'visibilidad', 'seller_id', 'category_id', 'user_id'];
    protected $allowSort = ['id', 'titulo', 'precio', 'created_at', 'updated_at'];


    public function category () {
        return $this->belongsTo(Category::class);
    }
    public function seller () {
        return $this->belongsTo(Seller::class);
    }
    public function complaints () {
        return $this->hasMany(Complaint::class);
    }
     public function images () {
        return $this->morphMany(Image::class, 'imageable');
    }
  public function users () {
    return $this->belongsToMany(User::class);
  }
  public function comment () {
    return $this->hasMany(comment::class);
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



