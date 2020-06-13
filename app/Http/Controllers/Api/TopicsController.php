<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Http\Resources\TopicResource;
use App\Http\Requests\Api\TopicRequest;
use Illuminate\Auth\Access\AuthorizationException;

class TopicsController extends Controller
{

    /**
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
}
