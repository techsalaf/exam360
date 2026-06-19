<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'latestReply'])->latest();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('ticket_id', 'like', "%{$term}%")
                  ->orWhere('subject', 'like', "%{$term}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
            });
        }

        // --- FILTERING LOGIC FIX ---
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;

            // Map frontend friendly status terms to database values
            if ($status === 'pending') {
                $status = 'open';
            } elseif ($status === 'answered') {
                $status = 'replied';
            }

            $query->where('status', $status);
        }

        $tickets = $query->paginate(15)->withQueryString();

        $stats = [
            [
                'label' => 'Total Tickets',
                'count' => Ticket::count(),
                'icon'  => 'fa-solid fa-ticket',
                'color' => 'primary',
                'key'   => 'all'
            ],
            [
                'label' => 'Pending',
                'count' => Ticket::where('status', 'open')->count(),
                'icon'  => 'fa-solid fa-hourglass-half',
                'color' => 'danger',
                'key'   => 'pending' // changed from 'open' to match sidebar link param if clicked from KPI
            ],
            [
                'label' => 'Answered',
                'count' => Ticket::where('status', 'replied')->count(),
                'icon'  => 'fa-solid fa-reply',
                'color' => 'info',
                'key'   => 'answered' // changed from 'replied'
            ],
            [
                'label' => 'Closed',
                'count' => Ticket::where('status', 'closed')->count(),
                'icon'  => 'fa-solid fa-check-circle',
                'color' => 'success',
                'key'   => 'closed'
            ]
        ];

        return view('admin.tickets.index', compact('tickets', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'replies.user']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:5120'
        ]);

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('support-tickets', 'public');
            }
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(), 
            'message' => $request->message,
            'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null
        ]);

        $ticket->update(['status' => 'replied']);

        if ($ticket->user) {
            $ticket->user->notify(new SystemNotification('ticket', [
                'title'   => 'Ticket Reply Received',
                'message' => "Admin has replied to your ticket #{$ticket->ticket_id}.",
                'url'     => route('user.tickets.show', $ticket->id),
                'icon'    => 'fa-solid fa-headset',
                'color'   => 'primary'
            ]));
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Ticket marked as closed.');
    }

    public function destroy(Ticket $ticket)
    {
        foreach ($ticket->replies as $reply) {
            if (!empty($reply->attachments)) {
                foreach ($reply->attachments as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}