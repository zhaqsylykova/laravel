<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\UserStoreMessage;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Store;
use App\Models\Order;

class ChatController extends Controller
{

    public function index($orderId)
    {
        $order = Order::with('messages')->findOrFail($orderId);
        return view('chat.index', compact('order'));
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fetchMessages(Request $request)
    {
        $messages = Message::where('store_id', $request->store_id)->get();

        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $userId = auth()->id();
        $storeId = $request->store_id;
        $content = $request->message;
        if (empty($content)) {
            return response()->json(['error' => 'Сообщение не может быть пустым'], 400);
        }

        $message = Message::create([
            'user_id' => $userId,
            'store_id' => $storeId,
            'content' => $content
        ]);

        event(new MessageSent($message));

        return response()->json(['message' => $message]);
    }
}
