<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Http\Resources\TopicResource;
use App\Http\Requests\Api\TopicRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class TopicsController extends Controller
{

    /**
     * 发布话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     */
    public function store(TopicRequest $request, Topic $topic): TopicResource
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    /**
     * 删除话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     * @throws AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic): TopicResource
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return new TopicResource($topic);
    }


    /**
     * 删除话题
     *
     * @param Topic $topic
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();

        return response(null, 204);
    }
}
