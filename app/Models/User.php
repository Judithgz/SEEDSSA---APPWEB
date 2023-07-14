<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Appointment;



class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];


    public static $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
    ];


    public static function createPatient(array $data){
        return self::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => 'paciente',
                'password' => Hash::make($data['password']),
            ]
        );
    }

    public function services(){
        return $this->belongsToMany(Services::class)->withTimestamps();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function scopePatients($query){
        return $query->where('role', 'paciente');
    }

    public function scopeUs($query){
        return $query->where('role', 'colaboradores');
    }

    public function asColaboradoresAppointments(){
        return $this->hasMany(Appointment::class, 'colaboradores_id');
    }

    public function attendedAppointments(){
        return $this->asColaboradoresAppointments()->where('status', 'Atendida');
    }

    public function canceledAppointments(){
        return $this->asColaboradoresAppointments()->where('status', 'Cancelada');
    }


    public function asPatientAppointments(){
        return $this->hasMany(Appointment::class, 'patient_id');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}