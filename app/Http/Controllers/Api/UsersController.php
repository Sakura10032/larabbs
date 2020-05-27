<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    /**
     * @param UserRequest $request
     * @return UserResource
     * @throws AuthenticationException
     */
    public function store(UserRequest $request): UserResource
    {
        $verifyData = Cache::get($request->verification_key);

        if (!$verifyData) {
            abort(403, '验证码已失效');
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            throw new AuthenticationException('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => $request->password,
        ]);

        // 清除验证码缓存
        Cache::forget($request->verification_key);

        return (new UserResource($user))->showSensitiveFields();
    }


    /**
     * @param User $user
     * @param Request $request
     * @return UserResource
     */
    public function show(User $user, Request $request): UserResource
    {
        return new UserResource($user);
    }


    /**
     * @param Request $request
     * @return UserResource
     */
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }


    /**
     * 更新用户信息
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function update(UserRequest $request): UserResource
    {
        $user = $request->user();

        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return (new UserResource($user))->showSensitiveFields();
    }
}
