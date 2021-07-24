<?php

namespace App\Observers;

class PostObserver
{
    /**
     * Handle the post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created($post)
    {
        $this->fillUpPublishInformation($post);
    }

    /**
     * Fill up the publishted information if its publishable.
     * 
     * @param  \App\Models\Post  $post
     * @return void
     */
    protected function fillUpPublishInformation($post)
    {
        $author = $post->author()->first();

        if (
            !$author 
            || !data_get(request(), 'data.attributes.publish_now') 
            || $author->isContributor()
        ) return;

        $post->forceFill([
            'published_by_id' => $author->id,
            'published_at' => now()->toDateTimeString(),
        ])->update();
    }
}
