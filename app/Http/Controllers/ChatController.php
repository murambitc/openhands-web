<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    public function index()
    {
        $messages = auth()->user()->chatMessages()->orderBy('created_at')->get();

        return response()->json($messages->map(fn ($m) => [
            'sender' => $m->sender,
            'message' => $m->message,
        ]));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $userMessage = $validated['message'];

        // Save user message
        ChatMessage::create([
            'user_id' => $user->id,
            'sender' => 'user',
            'message' => $userMessage,
        ]);

        try {
            // Get recent conversation context
            $history = $user->chatMessages()
                ->latest()
                ->take(10)
                ->get()
                ->reverse()
                ->map(fn ($m) => [
                    'role' => $m->sender === 'user' ? 'user' : 'assistant',
                    'content' => $m->message,
                ])
                ->values()
                ->toArray();

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => array_merge([
                    ['role' => 'system', 'content' => 'You are a supportive mental wellness assistant. Be empathetic, helpful, and concise. Never diagnose. For emergencies, advise contacting local crisis services.'],
                ], $history),
            ]);

            $reply = $response->choices[0]->message->content;

            ChatMessage::create([
                'user_id' => $user->id,
                'sender' => 'bot',
                'message' => $reply,
            ]);

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            \Log::error('Chat error: ' . $e->getMessage());
            return response()->json(['error' => 'AI service unavailable'], 500);
        }
    }
}