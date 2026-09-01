<?php

namespace App\Jobs;

use App\Models\Blog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
//use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SummarizePost implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(public Blog $blog)
    {
        $this->blog = $blog->withoutRelations();
    }

    // public function middleware(): array
    // {
    //     return [new RateLimited('summarize')];
    // }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::timeout(30)
            ->withHeaders(['x-goog-api-key' => config('services.gemini.key')])
            ->post('https://generativelanguage.googleapis.com/v1beta/models/' . config('services.gemini.model') . ':generateContent', [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Summarize the following blog post in 2-3 sentences:\n\n{$this->blog->content}"],
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            Log::error('Gemini summarization failed', [
                'blog_id' => $this->blog->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $response->throw();
        }

        $summary = trim($response->json('candidates.0.content.parts.0.text', ''));

        if ($summary !== '') {
            $this->blog->update(['excerpt' => $summary]);
        }
    }
}
