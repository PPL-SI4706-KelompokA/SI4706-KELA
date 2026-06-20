<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    public function index(Request $request)
    {
        $currentUserId = Auth::id();

        // Fetch all unique contact IDs that the user has chatted with
        $chatPartners = DB::table('pesans')
            ->where('id_pengirim', $currentUserId)
            ->orWhere('id_penerima', $currentUserId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($chat) use ($currentUserId) {
                return $chat->id_pengirim == $currentUserId ? $chat->id_penerima : $chat->id_pengirim;
            })
            ->unique()
            ->values();

        // Get users associated with those IDs and sort them by the latest chat order
        $contacts = User::whereIn('id_user', $chatPartners)
            ->get()
            ->sortBy(function ($user) use ($chatPartners) {
                return $chatPartners->search($user->id_user);
            })
            ->values();


        // Append target user from query parameter if starting a new conversation
        $activeContact = null;
        if ($request->has('user')) {
            $targetUserId = $request->query('user');
            $targetUser = User::find($targetUserId);
            if ($targetUser && $targetUser->id_user != $currentUserId) {
                $activeContact = $targetUser;
                // Prepend target user if they aren't already in the contacts list
                if (!$contacts->contains('id_user', $targetUser->id_user)) {
                    $contacts->prepend($targetUser);
                }
            }
        } elseif ($contacts->isNotEmpty()) {
            $activeContact = $contacts->first();
        }

        // Attach last message preview and unread counts for each contact
        foreach ($contacts as $contact) {
            $contact->last_message = Pesan::where(function ($q) use ($currentUserId, $contact) {
                $q->where('id_pengirim', $currentUserId)->where('id_penerima', $contact->id_user);
            })->orWhere(function ($q) use ($currentUserId, $contact) {
                $q->where('id_pengirim', $contact->id_user)->where('id_penerima', $currentUserId);
            })->latest('created_at')->first();

            $contact->unread_count = Pesan::where('id_pengirim', $contact->id_user)
                ->where('id_penerima', $currentUserId)
                ->where('status_baca', 0)
                ->count();
        }

        return view('pesan.index', compact('contacts', 'activeContact'));
    }

    public function getMessages($recipientId)
    {
        $currentUserId = Auth::id();

        // Fetch messages between sender and receiver
        $messages = Pesan::where(function ($q) use ($currentUserId, $recipientId) {
            $q->where('id_pengirim', $currentUserId)->where('id_penerima', $recipientId);
        })->orWhere(function ($q) use ($currentUserId, $recipientId) {
            $q->where('id_pengirim', $recipientId)->where('id_penerima', $currentUserId);
        })->orderBy('created_at', 'asc')->get();

        // Mark all messages sent by recipient to current user as read
        Pesan::where('id_pengirim', $recipientId)
            ->where('id_penerima', $currentUserId)
            ->where('status_baca', 0)
            ->update(['status_baca' => 1]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'id_penerima' => 'required|exists:users,id_user',
            'pesan'       => 'required|string',
        ]);

        $message = Pesan::create([
            'id_pengirim' => Auth::id(),
            'id_penerima' => $request->id_penerima,
            'pesan'       => $request->pesan,
            'status_baca' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
     }
 
     public function deleteConversation($recipientId)
     {
         $currentUserId = Auth::id();
 
         // Delete all messages between current user and the recipient
         Pesan::where(function ($q) use ($currentUserId, $recipientId) {
             $q->where('id_pengirim', $currentUserId)->where('id_penerima', $recipientId);
         })->orWhere(function ($q) use ($currentUserId, $recipientId) {
             $q->where('id_pengirim', $recipientId)->where('id_penerima', $currentUserId);
         })->delete();
 
         return response()->json([
             'success' => true,
             'message' => 'Percakapan berhasil dihapus.'
         ]);
     }
 }

