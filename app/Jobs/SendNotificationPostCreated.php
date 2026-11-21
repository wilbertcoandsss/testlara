<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNotificationPostCreated implements ShouldQueue
{
    use Queueable;
    public $post;
    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        //
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Kirim Email
        // Send apapun yg berat
        logger('Notifikasi dikirim untuk post: ' . $this->post->name . ' ' . $this ->post->user->name);
        //Mail::to ....

    }
}
