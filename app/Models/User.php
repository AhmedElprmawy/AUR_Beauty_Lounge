<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword; // ✅ أضف CanResetPassword

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'is_admin',
        'edit_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'edit_password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    /**
     * Check whether the admin edit password is set for this user.
     */
    public function hasEditPassword(): bool
    {
        return !empty($this->edit_password);
    }

    /**
     * Verify a provided password against the user's edit password.
     */
    public function verifyEditPassword(string $password): bool
    {
        if (empty($this->edit_password)) {
            return false;
        }

        return Hash::check($password, $this->edit_password);
    }

    /**
     * Set a new edit password for the user.
     */
    public function setEditPassword(string $password): void
    {
        $this->update([
            'edit_password' => Hash::make($password)
        ]);
    }

    /**
     * Remove the edit password.
     */
    public function removeEditPassword(): void
    {
        $this->update([
            'edit_password' => null
        ]);
    }
}