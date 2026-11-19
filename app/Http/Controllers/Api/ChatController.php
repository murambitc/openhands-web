<?php

namespace App\Http\Controllers\Api;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends ApiController
{
    public function index(Request $request)
    {
        $messages = $request->user()
            ->chatMessages()
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'id'         => $m->id,
                'sender'     => $m->sender,
                'message'    => $m->message,
                'created_at' => $m->created_at->toIso8601String(),
            ]);

        return $this->ok($messages);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        ChatMessage::create([
            'user_id' => $user->id,
            'sender'  => 'user',
            'message' => $validated['message'],
        ]);

        try {
            $history = $user->chatMessages()
                ->latest()
                ->take(10)
                ->get()
                ->reverse()
                ->map(fn ($m) => [
                    'role'    => $m->sender === 'user' ? 'user' : 'assistant',
                    'content' => $m->message,
                ])
                ->values()
                ->toArray();

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => array_merge(
                    [['role' => 'system', 'content' => 'You are a supportive mental wellness assistant. Be empathetic, helpful, and concise. For emergencies, advise contacting local crisis services.']],
                    $history
                ),
            ]);

            $reply = $response->choices[0]->message->content;

            $bot = ChatMessage::create([
                'user_id' => $user->id,
                'sender'  => 'bot',
                'message' => $reply,
            ]);

            return $this->ok([
                'reply' => $reply,
                'id'    => $bot->id,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Chat error: ' . $e->getMessage());
            return $this->fail('AI service unavailable. Please try again.', 503);
        }
    }

    public function clear(Request $request)
    {
        $request->user()->chatMessages()->delete();
        return $this->ok(null, 'Chat cleared.');
    }
}