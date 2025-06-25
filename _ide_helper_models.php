<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property CheckAttendStatus $status
 * @property string $tanggal
 * @property string|null $jam_masuk
 * @property string|null $jam_keluar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereJamKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InternAttend whereUserId($value)
 * @mixin \Eloquent
 */
	class InternAttend extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $user_id
 * @property string $created
 * @property string $task
 * @property string $description
 * @property string|null $deadline
 * @property CheckJobStatus|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereTask($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobIntern whereCreated($value)
 * @mixin \Eloquent
 */
	class JobIntern extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $tokenable_type
 * @property string $tokenable_id
 * @property string $name
 * @property string $token
 * @property array<array-key, mixed>|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $tokenable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PersonalAccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property string $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InternAttend> $intern_attends
 * @property-read int|null $intern_attends_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobIntern> $job_interns
 * @property-read int|null $job_interns_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

