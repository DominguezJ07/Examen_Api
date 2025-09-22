<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
     'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'email', 'password', 'activo'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
     // LISTAS BLANCAS PARA API
    protected $allowIncluded = ['Seller', 'Comment', 'Chatsuport', 'role', 'Complain', 'Image', 'Publication'];
    protected $allowFilter = ['id', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'activo', 'role_id'];
    protected $allowSort = ['id', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'activo', 'created_at', 'updated_at'];

    public function seller (){
        return $this->hasMany(Seller::class);
    }
    public function comment () {
        return $this->hasMany(Comment::class);
    }
    public function chatSuport () {
        return $this->hasMany(ChatSuport::class);
    }
    public function role () {
        return $this->belongsTo(Role::class);
    }
    public function complain () {
        return $this->hasMany(Complaint::class);
    }
     public function image () {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function publication () {
        return $this->belongsToMany(Publication::class);
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


